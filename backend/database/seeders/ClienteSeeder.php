<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Cliente;

class ClienteSeeder extends Seeder
{

    public function run(): void
    {

        Cliente::create([

            'nombre' => 'Juan Perez',

            'dni' => '12345678',

            'telefono' => '999999999'
        ]);

        Cliente::create([

            'nombre' => 'Maria Lopez',

            'dni' => '87654321',

            'telefono' => '988888888'
        ]);
    }
}
