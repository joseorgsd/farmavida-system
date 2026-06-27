<?php

use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {

        Schema::create(

            'movimientos_stock',

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
                | TIPO
                |--------------------------------------------------------------------------
                */

                $table->enum(

                    'tipo',

                    [

                        'ENTRADA',

                        'SALIDA'
                    ]
                );

                /*
                |--------------------------------------------------------------------------
                | MOTIVO
                |--------------------------------------------------------------------------
                */

                $table->string(
                    'motivo'
                );

                /*
                |--------------------------------------------------------------------------
                | CANTIDAD
                |--------------------------------------------------------------------------
                */

                $table->integer(
                    'cantidad'
                );

                /*
                |--------------------------------------------------------------------------
                | STOCKS
                |--------------------------------------------------------------------------
                */

                $table->integer(
                    'stock_anterior'
                );

                $table->integer(
                    'stock_nuevo'
                );

                /*
                |--------------------------------------------------------------------------
                | USER
                |--------------------------------------------------------------------------
                */

                $table->foreignId(
                    'user_id'
                )->constrained();

                $table->timestamps();
            }
        );
    }

    public function down(): void
    {

        Schema::dropIfExists(
            'movimientos_stock'
        );
    }
};