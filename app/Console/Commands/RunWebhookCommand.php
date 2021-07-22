<?php

namespace App\Console\Commands;

use App\Repositories\ApplicationRepository;
use App\Repositories\HealthLogRepository;
use App\Repositories\WebhookRepository;
use App\SystemChecks\Webhook;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;


class RunWebhookCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system-checks:run-webhooks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute webhooks';

    protected $applicationRepository;

    protected $webhookRepository;

    protected $healthLogRepository;

    protected $webhook;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        ApplicationRepository $applicationRepository,
        HealthLogRepository $healthLogRepository,
        WebhookRepository $webhookRepository,
        Webhook $webhook
    )
    {
        parent::__construct();

        $this->applicationRepository = $applicationRepository;

        $this->healthLogRepository = $healthLogRepository;

        $this->webhookRepository = $webhookRepository;

        $this->webhook = $webhook;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $active_webhooks = $this->webhookRepository->getActiveWebhooks();

        if ($active_webhooks) {
            if ($active_webhooks) {
                foreach ($active_webhooks as $active_webhook) {

                    foreach ($active_webhook->applications as $_active_application) {
                        $application = $this->applicationRepository->find($_active_application->application_id);

                        if ($application->is_monitored) {
                            $log = $this->healthLogRepository->getRecentApplicationLog($application->id);

                            if ($log) {
                                if ($active_webhook->send_all_codes == 0 and $log->http_code < 300) {
                                    continue;
                                }

                                $content = "App Name: {$application->name}\n";
                                $content .= "HTTP Status: {$log->http_code}\n";
                                $content .= "Timestamp: {$log->created_at->format('M d, Y H:i:s')} (".config('app.timezone').")";

                                $logs = [
                                    'text' => $content
                                ];

                                $response = $this->webhook->send($active_webhook->url, $logs);

                                if ($response->getStatusCode() != 200) {
                                    Log::alert("Unable to send log on {$active_webhook->name}|response: ".serialize($response));
                                }
                            }
                        }
                    }

                }

            }
        }
    }
}
