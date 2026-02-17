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
        $schedule->command('analytics:report')->everyFiveSeconds();

        $schedule->command('analytics:device-report')->everyTwoHours();
        // Executa o relatório diário todos os dias à meia-noite
        $schedule->command('analytics:daily-report')->dailyAt('00:00');

        // Executa o relatório semanal toda segunda-feira às 6h da manhã
        $schedule->command('analytics:weekly-report')->weeklyOn(1, '06:00');

        // Executa o relatório de fontes de tráfego todos os dias às 8h da manhã
        $schedule->command('analytics:traffic-source-report')->dailyAt('08:00');

        // Executa o relatório de páginas mais acessadas todos os dias às 10h da manhã
        $schedule->command('analytics:top-pages-report')->dailyAt('10:00');
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
