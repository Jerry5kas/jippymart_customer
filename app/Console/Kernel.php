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
        // DISABLED for shared hosting to prevent resource limit issues
        // All scheduled tasks commented out to avoid 503/508 errors
        
        // Generate lightweight sitemap weekly (DISABLED for shared hosting)
        // $schedule->command('sitemap:generate-lightweight')
        //          ->weekly()
        //          ->sundays()
        //          ->at('03:00')
        //          ->withoutOverlapping()
        //          ->runInBackground();

        // Original heavy sitemap generation - DISABLED for shared hosting
        // $schedule->command('sitemap:generate')
        //          ->dailyAt('02:00')
        //          ->withoutOverlapping()
        //          ->runInBackground();

        // TODO: Re-enable scheduled tasks when moving to dedicated server or VPS
        // For now, run sitemap generation manually if needed
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
