<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Generate lightweight sitemap weekly (shared hosting friendly)
        $schedule->command('sitemap:generate-lightweight')
                 ->weekly()
                 ->sundays()
                 ->at('03:00')
                 ->withoutOverlapping()
                 ->runInBackground();

        // Original heavy sitemap generation - DISABLED for shared hosting
        // $schedule->command('sitemap:generate')
        //          ->dailyAt('02:00')
        //          ->withoutOverlapping()
        //          ->runInBackground();

        // TODO: Re-enable heavy sitemap when moving to dedicated server or VPS
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
