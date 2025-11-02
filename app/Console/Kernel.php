<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Send task due reminders every hour
        $schedule->command('notifications:task-due-reminders')
            ->hourly()
            ->withoutOverlapping();

        // Send task overdue notifications daily at 9 AM
        $schedule->command('notifications:task-overdue')
            ->dailyAt('09:00')
            ->withoutOverlapping();

        // Send inspection due reminders every hour
        $schedule->command('notifications:inspection-due-reminders')
            ->hourly()
            ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
