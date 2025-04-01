<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PedidoSeeder extends Seeder
{
    public function run()
    {
        DB::table('pedidos')->insert([
            [
                'usuario_id' => 1, // Asegúrate de que este usuario exista en la tabla 'users'
                'total' => 100.50,
                'estado' => 'pendiente', // Valor válido según la migración
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'usuario_id' => 2, // Asegúrate de que este usuario exista en la tabla 'users'
                'total' => 250.75,
                'estado' => 'entregado', // Valor válido según la migración
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
