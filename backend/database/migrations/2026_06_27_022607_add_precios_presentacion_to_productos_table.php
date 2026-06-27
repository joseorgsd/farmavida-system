<?php

use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::table('productos', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | SOLO SI NO EXISTEN
            |--------------------------------------------------------------------------
            */

            if (

                !Schema::hasColumn(
                    'productos',
                    'precio_blister'
                )

            ) {

                $table->decimal(

                    'precio_blister',
                    10,
                    2

                )->default(0);
            }

            if (

                !Schema::hasColumn(
                    'productos',
                    'precio_unidad'
                )

            ) {

                $table->decimal(

                    'precio_unidad',
                    10,
                    2

                )->default(0);
            }
        });
    }

    public function down(): void
    {

        Schema::table('productos', function (Blueprint $table) {

            $table->dropColumn([

                'precio_blister',

                'precio_unidad'
            ]);
        });
    }
};