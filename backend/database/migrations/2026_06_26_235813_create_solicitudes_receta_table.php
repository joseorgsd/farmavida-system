<?php

use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {

        Schema::create(

            'solicitudes_receta',

            function (
                Blueprint $table
            ) {

                $table->id();

                /*
                |--------------------------------------------------------------------------
                | CLIENTE
                |--------------------------------------------------------------------------
                */

                $table->foreignId(
                    'cliente_id'
                )->constrained();

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
                | CAJERO
                |--------------------------------------------------------------------------
                */

                $table->foreignId(
                    'cajero_id'
                )->constrained(
                    'users'
                );

                /*
                |--------------------------------------------------------------------------
                | QUIMICO
                |--------------------------------------------------------------------------
                */

                $table->foreignId(
                    'quimico_id'
                )->nullable()->constrained(
                    'users'
                );

                /*
                |--------------------------------------------------------------------------
                | CANTIDAD SOLICITADA
                |--------------------------------------------------------------------------
                */

                $table->integer(
                    'cantidad_solicitada'
                );

                /*
                |--------------------------------------------------------------------------
                | CANTIDAD APROBADA
                |--------------------------------------------------------------------------
                */

                $table->integer(
                    'cantidad_aprobada'
                )->nullable();

                /*
                |--------------------------------------------------------------------------
                | ESTADO
                |--------------------------------------------------------------------------
                */

                $table->enum(

                    'estado',

                    [

                        'PENDIENTE',

                        'APROBADO',

                        'RECHAZADO'
                    ]

                )->default(
                    'PENDIENTE'
                );

                /*
                |--------------------------------------------------------------------------
                | RECETA
                |--------------------------------------------------------------------------
                */

                $table->string(
                    'numero_receta'
                )->nullable();

                $table->string(
                    'medico'
                )->nullable();

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
            'solicitudes_receta'
        );
    }
};