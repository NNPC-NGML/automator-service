<?php

namespace App\Console;

use App\Service\AutomatorTaskService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('queue:work --stop-when-empty')->everyMinute();
        $schedule->call(function () {
            // Call your service to handle the task assignment
            app(AutomatorTaskService::class)->TriggerFrequentProcessFlow();
        })->everyMinute();

        // while (true) {
        //     Artisan::call('queue:work', [
        //         '--stop-when-empty' => true,
        //     ]);
        //     usleep(500000); // 0.5 seconds (500,000 microseconds)
        // }
        // $schedule->call(function () {
        //     // Call your service to handle the task assignment
        //     app(AutomatorTaskService::class)->TriggerFrequentProcessFlow();
        // })->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
