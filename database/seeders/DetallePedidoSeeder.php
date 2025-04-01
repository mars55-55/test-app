<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DetallePedidoSeeder extends Seeder
{
    public function run()
    {
        DB::table('detalle_pedidos')->insert([
            [
                'pedido_id' => 1, // Asegúrate de que este pedido exista en la tabla 'pedidos'
                'producto_id' => 5, // Cambiado de 1 a 5 para coincidir con la tabla 'productos'
                'cantidad' => 2,
                'precio' => 50.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pedido_id' => 1,
                'producto_id' => 6, // Cambiado de 2 a 6 para coincidir con la tabla 'productos'
                'cantidad' => 1,
                'precio' => 75.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pedido_id' => 2,
                'producto_id' => 5, // Cambiado de 1 a 5 para coincidir con la tabla 'productos'
                'cantidad' => 3,
                'precio' => 50.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
