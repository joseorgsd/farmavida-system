<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\User;

use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    public function run(): void
    {

        User::firstOrCreate([

            'name' => 'Administrador',

            'email' => 'admin@farmavida.com',

            'password' => Hash::make('12345678'),

            'rol' => 'ADMIN'
        ]);

        User::firstOrCreate([

            'name' => 'Cajero',

            'email' => 'cajero@farmavida.com',

            'password' => Hash::make('12345678'),

            'rol' => 'CAJERO'
        ]);

        User::firstOrCreate([

            'name' => 'Quimico',

            'email' => 'quimico@farmavida.com',

            'password' => Hash::make('12345678'),

            'rol' => 'QUIMICO'
        ]);
    }
}

