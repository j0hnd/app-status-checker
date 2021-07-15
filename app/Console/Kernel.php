<?php

namespace App\Console;

use App\Console\Commands\CheckStatusCommand;
use App\Console\Commands\RunWebhookCommand;
use App\Repositories\ApplicationRepository;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        CheckStatusCommand::class,
        RunWebhookCommand::class
    ];

    protected $applicationRepository;


    public function __construct(
        Application $app,
        Dispatcher $events,
        ApplicationRepository $applicationRepository
    )
    {
        parent::__construct($app, $events);

        $this->applicationRepository = $applicationRepository;
    }

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // execute webhooks
        $schedule->command('system-checks:run-webhooks')->everyMinute();

        // run http checks on each for monitor applications/apis
        $applications = $this->applicationRepository->findApplicationsForMonitoring();

        if ($applications) {
            foreach ($applications as $application) {
                switch ($application->frequency) {
                    case "everyMinute":
                        $schedule->command("system-checks:apache {$application->id} '{$application->application_url}'")->everyMinute();
                        break;

                    case "everyTwoMinutes":
                        $schedule->command("system-checks:apache {$application->id} '{$application->application_url}'")->everyTwoMinutes();
                        break;

                    case "everyThreeMinutes":
                        $schedule->command("system-checks:apache {$application->id} '{$application->application_url}'")->everyThreeMinutes();
                        break;

                    case "everyFiveMinutes":
                        $schedule->command("system-checks:apache {$application->id} '{$application->application_url}'")->everyFiveMinutes();
                        break;

                    case "everyFifteenMinutes":
                        $schedule->command("system-checks:apache {$application->id} '{$application->application_url}'")->everyFifteenMinutes();
                        break;

                    case "everyThirtyMinutes":
                        $schedule->command("system-checks:apache {$application->id} '{$application->application_url}'")->everyThirtyMinutes();
                        break;

                    case "hourly":
                        $schedule->command("system-checks:apache {$application->id} '{$application->application_url}'")->hourly();
                        break;

                    case "daily":
                        $schedule->command("system-checks:apache {$application->id} '{$application->application_url}'")->daily();
                        break;
                }
            }
        }

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
