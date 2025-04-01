<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductoSeeder extends Seeder
{
    public function run()
    {
        DB::table('productos')->insert([
            [
                'nombre' => 'Producto 1',
                'descripcion' => 'Descripción del producto 1',
                'precio' => 50.00,
                'cantidad_disponible' => 100, // Cambiado de 'stock' a 'cantidad_disponible'
                'agricultor_id' => 1, // Asegúrate de que este usuario exista en la tabla 'users'
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nombre' => 'Producto 2',
                'descripcion' => 'Descripción del producto 2',
                'precio' => 75.00,
                'cantidad_disponible' => 200, // Cambiado de 'stock' a 'cantidad_disponible'
                'agricultor_id' => 2, // Asegúrate de que este usuario exista en la tabla 'users'
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
