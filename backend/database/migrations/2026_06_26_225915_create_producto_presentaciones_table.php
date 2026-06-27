<?php

use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {

        Schema::create(

            'producto_presentaciones',

            function (
                Blueprint $table
            ) {

                $table->id();

                /*
                |--------------------------------------------------------------------------
                | PRODUCTO
                |--------------------------------------------------------------------------
                */

                $table->foreignId(
                    'producto_id'
                )->constrained();

                /*
                |--------------------------------------------------------------------------
                | UNIDAD
                |--------------------------------------------------------------------------
                */

                $table->foreignId(
                    'unidad_medida_id'
                )->constrained(
                    'unidades_medida'
                );

                /*
                |--------------------------------------------------------------------------
                | FACTOR
                |--------------------------------------------------------------------------
                |
                | caja = 100
                | blister = 10
                | unidad = 1
                |
                */

                $table->integer(
                    'factor'
                );

                /*
                |--------------------------------------------------------------------------
                | PRECIO
                |--------------------------------------------------------------------------
                */

                $table->decimal(
                    'precio',
                    10,
                    2
                );

                /*
                |--------------------------------------------------------------------------
                | DEFAULT
                |--------------------------------------------------------------------------
                */

                $table->boolean(
                    'es_principal'
                )->default(false);

                $table->timestamps();
            }
        );
    }

    public function down(): void
    {

        Schema::dropIfExists(
            'producto_presentaciones'
        );
    }
};