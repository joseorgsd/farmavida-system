<?php

use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {

        Schema::create(

            'productos_restringidos',

            function (
                Blueprint $table
            ) {

                $table->id();

                $table->foreignId(
                    'producto_id'
                )->constrained();

                /*
                |--------------------------------------------------------------------------
                | TIPOS
                |--------------------------------------------------------------------------
                */

                $table->boolean(
                    'requiere_receta'
                )->default(false);

                $table->boolean(
                    'requiere_dni'
                )->default(false);

                $table->boolean(
                    'requiere_medico'
                )->default(false);

                $table->boolean(
                    'controlado'
                )->default(false);

                /*
                |--------------------------------------------------------------------------
                | OBS
                |--------------------------------------------------------------------------
                */

                $table->text(
                    'observacion'
                )->nullable();

                $table->timestamps();
            }
        );
    }

    public function down(): void
    {

        Schema::dropIfExists(
            'productos_restringidos'
        );
    }
};