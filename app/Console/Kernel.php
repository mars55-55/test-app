<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\ImportDaneSipsaTracking::class, // Agregamos el nuevo comando
    ];

    protected function schedule(Schedule $schedule)
    {
        // Ejecuta el comando cada minuto
        $schedule->command('tracking:import-sipsa')->everyMinute()
            ->onFailure(function () {
                \Log::warning('ImportDaneSipsaTracking: Falló la conexión con el DANE');
            })
            ->onSuccess(function () {
                \Log::info('ImportDaneSipsaTracking: Datos importados correctamente');
            });
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
