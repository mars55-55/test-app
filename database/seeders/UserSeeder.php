<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'id' => 1, // Asegúrate de que este ID coincida con el agricultor_id en el seeder de productos
                'name' => 'Agricultor 1',
                'email' => 'agricultor1@example.com',
                'password' => Hash::make('password'),
                'role_id' => 2, // Asegúrate de que el rol 'agricultor' exista en la tabla 'roles'
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2, // Asegúrate de que este ID coincida con el agricultor_id en el seeder de productos
                'name' => 'Agricultor 2',
                'email' => 'agricultor2@example.com',
                'password' => Hash::make('password'),
                'role_id' => 2, // Asegúrate de que el rol 'agricultor' exista en la tabla 'roles'
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
