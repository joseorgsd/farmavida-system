<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\UnidadMedida;

class UnidadMedidaSeeder extends Seeder
{

    public function run(): void
    {

        UnidadMedida::insert([

            [

                'nombre' => 'UNIDAD',

                'codigo' => 'UND'
            ],

            [

                'nombre' => 'BLISTER',

                'codigo' => 'BLS'
            ],

            [

                'nombre' => 'CAJA',

                'codigo' => 'CJA'
            ]
        ]);
    }
}