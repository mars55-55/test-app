<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tracking;
use App\Models\User;
use SoapClient;

class ImportDaneSipsaTracking extends Command
{
    protected $signature = 'tracking:import-sipsa';
    protected $description = 'Importa precios agrícolas desde SIPSA (DANE) vía SOAP';

    public function handle()
    {
        $wsdlUrl = 'http://appweb.dane.gov.co/sipsaWS/SrvSipsaUpraBeanService?WSDL';
        $client = null;

        try {
            $client = new SoapClient($wsdlUrl, [
                'trace' => true,
                'exceptions' => true,
                'cache_wsdl' => WSDL_CACHE_NONE,
                'connection_timeout' => 15, // segundos
            ]);

            $this->info("Conectado al WSDL del DANE correctamente.");

            // Llamada al método
            $response = $client->__soapCall('promediosSipsaCiudad', []);

            $agricultor = User::whereHas('role', function($q) {
                $q->where('name', 'agricultor');
            })->first();

            if (!$agricultor) {
                $this->error('No hay agricultores registrados.');
                return;
            }

            foreach ($response->return as $item) {
                Tracking::updateOrCreate(
                    [
                        'producto' => $item->producto ?? 'Desconocido',
                        'fecha_siembra' => $item->fechaCaptura ?? now()->toDateString(),
                        'fecha_cosecha' => null,
                    ],
                    [
                        'agricultor_id' => $agricultor->id,
                        'estimacion_ganancia' => $item->precioPromedio ?? 0,
                        'notas' => $item->ciudad ?? '',
                    ]
                );
            }

            $this->info('Datos de SIPSA importados correctamente.');

        } catch (\SoapFault $e) {
            $this->error("Error SOAP o servidor no disponible: " . $e->getMessage());
        } catch (\Exception $e) {
            $this->error("Error general: " . $e->getMessage());
        }
    }
}
