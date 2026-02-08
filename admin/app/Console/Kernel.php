<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        // Verificar planos expirados todo dia à meia-noite
        $schedule->command('planos:check-expired')->dailyAt('00:00');
        
        // Executa o comando de verificação de visibilidade de usuários semanalmente
        $schedule->command('users:check-visibility')->weekly();
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
