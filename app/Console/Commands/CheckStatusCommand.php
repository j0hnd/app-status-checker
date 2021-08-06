<?php

namespace App\Console\Commands;

use App\Models\HealthLog;
use App\SystemChecks\Webhook;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Events\AppStatusUpdated;
use App\Models\EndpointDetail;
use App\Repositories\ApplicationRepository;
use App\SystemChecks\Apache;
use App\Repositories\HealthLogRepository;
use Illuminate\Support\Facades\Log;
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

    protected $webhook;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        ApplicationRepository $applicationRepository,
        HealthLogRepository $healthLogRepository,
        Apache $apache,
        Webhook $webhook
    )
    {
        parent::__construct();

        $this->applicationRepository = $applicationRepository;
        $this->healthLogRepository = $healthLogRepository;
        $this->apache = $apache;
        $this->webhook = $webhook;
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

                if (! empty($application->endpoint_detail->content_type)) {
                    $options['content_type'] = $application->endpoint_detail->content_type;
                }

                $authorization_type = $application->endpoint_detail->authorization_type;

                if (empty($application->endpoint_detail->current_token)) {
                    if (! empty($application->endpoint_detail->token_url)) {
                        $token = $this->request_token($application->endpoint_detail->token_url, $this->get_request_token_credentials($application->endpoint_detail, $authorization_type), $authorization_type);
                        $options['bearer_token'] = $token;

                        // save token
                        $application->endpoint_detail->current_token = $token;
                        $application->endpoint_detail->save();
                    }
                } else {
                    if ($this->is_token_expired($application->endpoint_detail->current_token)) {
                        $token =$this->request_token($application->endpoint_detail->token_url, $this->get_request_token_credentials($application->endpoint_detail, $authorization_type), $authorization_type);
                    } else {
                        $token = $application->endpoint_detail->current_token;
                    }

                    if ($authorization_type == 'basic_auth' and ! empty($application->endpoint_detail->login_as) and ! empty($application->endpoint_detail->login_as_token_url)) {
                        $token = $this->request_token($application->endpoint_detail->login_as_token_url, ['email' => $application->endpoint_detail->login_as], null, $token);
                    }

                    $options['bearer_token'] = $token;

                    // save token
                    $application->endpoint_detail->current_token = $token;
                    $application->endpoint_detail->save();

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

        $health_log = new HealthLog();
        $health_log->application_id = $application_id;
        $health_log->http_code = $apache_response['http_code'];
        $health_log->extras = $apache_response['extras'];

        if ($health_log->save()) {
            event(new AppStatusUpdated($application->application_code));

            $this->send_log($application_id);
        }


        return 0;
    }

    private function send_log($application_id)
    {
        $application = $this->applicationRepository->find($application_id);

        if ($application->webhooks) {
            foreach ($application->webhooks as $webhook) {
                if ($application->is_monitored and $webhook->webhooks->is_active) {
                    $log = $this->healthLogRepository->getRecentApplicationLog($application->id);

                    if ($log) {
                        if ($webhook->webhooks->send_all_codes == 0 and $log->http_code < 300) {
                            continue;
                        }

                        $content = "App Name: {$application->name}\n";
                        $content .= "HTTP Status: {$log->http_code}\n";
                        $content .= "Timestamp: {$log->created_at->format('M d, Y H:i:s')} (".config('app.timezone').")";

                        $response = $this->webhook->send($webhook->webhooks->url, [
                            'text' => $content
                        ]);

                        if ($response->getStatusCode() != 200) {
                            Log::alert("Unable to send log on {$webhook->webhooks->name}|response: ".serialize($response));
                        }

                        Log::info("Logs of {$application->name} was sent to {$webhook->webhooks->name} at {$log->created_at->format('M d, Y H:i:s')}");
                    }
                }
            }
        }
    }

    private function request_token($url, $data, $authorization_type = null, $token = null)
    {
        if (is_null($data)) {
            return null;
        }

        if (is_null($token)) {
            $response = Http::post($url, $data);
        } else {
            $response = Http::withToken($token)->post($url, $data);
        }

        if ($response->successful()) {
            if ($authorization_type == 'basic_auth' or is_null($authorization_type)) {
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
                $credentials['password'] = base64_decode($endpointDetail->password);
                break;

            case "api_key_auth":
                $credentials['app_id'] = $endpointDetail->app_key;
                $credentials['app_key'] = $endpointDetail->app_secret;
                break;
        }

        return ! isset($credentials) ? null : $credentials;
    }
}
