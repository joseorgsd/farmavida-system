<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{

    public function run(): void
    {

        /*
        |--------------------------------------------------------------------------
        | ROLES
        |--------------------------------------------------------------------------
        */

        Role::create([

            'name' => 'ADMIN'

        ]);

        Role::create([

            'name' => 'CAJERO'

        ]);

        Role::create([

            'name' => 'ALMACEN'

        ]);

        Role::create([

            'name' => 'QUIMICO'

        ]);
    }
}