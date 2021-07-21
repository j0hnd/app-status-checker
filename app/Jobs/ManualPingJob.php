<?php

namespace App\Jobs;

use App\Events\ManualPingUpdated;
use App\Repositories\ApplicationRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class ManualPingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $applications;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($applications)
    {
        $this->applications = $applications;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->applications) {
            foreach ($this->applications as $application) {
                Artisan::call('system-checks:apache', [
                    'application_id' => $application->id,
                    'application_url' => $application->application_url,
                ]);
            }
        }
    }
}
