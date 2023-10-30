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
        $schedule->command('sync:go-cache 300    --isolated')->everyMinute();
        $schedule->command('sync:go-cache 3600   --isolated')->hourly();
        $schedule->command('sync:go-cache 43200  --isolated')->everyThreeHours();
        $schedule->command('sync:go-cache 86400  --isolated')->hourlyAt(0);
        $schedule->command('sync:go-cache        --isolated')->saturdays()->dailyAt('03:00');
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
