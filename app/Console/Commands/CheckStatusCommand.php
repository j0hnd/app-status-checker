<?php

namespace App\Console\Commands;

use App\Models\EndpointDetail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Repositories\ApplicationRepository;
use App\SystemChecks\Apache;
use App\Repositories\HealthLogRepository;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;


class CheckStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system-checks:apache {application_id} {application_url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the application\'s HTTP status code';

    protected $applicationRepository;

    protected $healthLogRepository;

    protected $apache;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        ApplicationRepository $applicationRepository,
        HealthLogRepository $healthLogRepository,
        Apache $apache
    )
    {
        parent::__construct();

        $this->applicationRepository = $applicationRepository;
        $this->healthLogRepository = $healthLogRepository;
        $this->apache = $apache;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $application_id = $this->argument('application_id');
        $application_url = $this->argument('application_url');

        $application = $this->applicationRepository->find($application_id);

        $options = null;

        if ($application) {
            if (! is_null($application->endpoint_detail)) {
                $options['method'] = $application->endpoint_detail->method;
                $options['field_type'] = $application->endpoint_detail->field_type;

                $authorization_type = $application->endpoint_detail->authorization_type;

                if (empty($application->endpoint_detail->current_token)) {
                    if (! empty($application->endpoint_detail->token_url)) {
                        $token = $this->request_token($application->endpoint_detail->token_url, $authorization_type, $this->get_request_token_credentials($application->endpoint_detail, $authorization_type));
                        $options['bearer_token'] = $token;

                        // save token
                        $application->endpoint_detail->current_token = $token;
                        $application->endpoint_detail->save();
                    }
                } else {
                    if ($this->is_token_expired($application->endpoint_detail->current_token)) {
                        $token = $this->request_token($application->endpoint_detail->token_url, $authorization_type, $this->get_request_token_credentials($application->endpoint_detail, $authorization_type));
                        $options['bearer_token'] = $token;

                        // save token
                        $application->endpoint_detail->current_token = $token;
                        $application->endpoint_detail->save();
                    }

                    $options['bearer_token'] = $application->endpoint_detail->current_token;
                }
            }

            if (! is_null($application->endpoint_params)) {
                foreach ($application->endpoint_params as $param) {
                    $options['fields'] = [$param->key => $param->value];
                }
            }
        }

        $apache_response = $this->apache->resolve($application_url, $options);

        $this->healthLogRepository->create([
            'application_id' => $application_id,
            'http_code' => $apache_response['http_code'],
            'extras' => $apache_response['extras']
        ]);

        return 0;
    }

    private function request_token($url, $authorization_type, $data)
    {
        if (is_null($data)) {
            return null;
        }

        $response = Http::post($url, $data);

        if ($response->successful()) {
            if ($authorization_type == 'basic_auth') {
                $token = (json_decode($response->body(), true))['data']['token'];
            }

            if ($authorization_type == 'api_key_auth') {
                $token = (json_decode($response->body(), true))['token'];
            }
        }

        return isset($token) ? $token : null;
    }

    private function is_token_expired($token)
    {
        $exp = (new Parser(new JoseEncoder()))->parse((string) $token)->claims()->get('exp');

        return !(($exp->getTimestamp() > (new \DateTime())->getTimestamp()));
    }

    private function get_request_token_credentials(EndpointDetail $endpointDetail, $authorization_type)
    {
        switch ($authorization_type) {
            case "basic_auth":
                $credentials['username'] = $endpointDetail->username;
                $credentials['password'] = $endpointDetail->password;
                break;

            case "api_key_auth":
                $credentials['app_id'] = $endpointDetail->app_key;
                $credentials['app_key'] = $endpointDetail->app_secret;
                break;
        }

        return ! isset($credentials) ? null : $credentials;
    }
}
