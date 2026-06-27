<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create(

            'movimiento_inventarios',

            function (Blueprint $table) {

                $table->id();

                /*
                |--------------------------------------------------------------------------
                | RELACIONES
                |--------------------------------------------------------------------------
                */

                $table->foreignId('producto_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->foreignId('user_id')
                    ->nullable()
                    ->constrained()
                    ->nullOnDelete();

                /*
                |--------------------------------------------------------------------------
                | MOVIMIENTO
                |--------------------------------------------------------------------------
                */

                $table->enum(

                    'tipo_movimiento',

                    [

                        'ENTRADA',

                        'SALIDA',

                        'AJUSTE'

                    ]

                );

                /*
                |--------------------------------------------------------------------------
                | DETALLE
                |--------------------------------------------------------------------------
                */

                $table->integer('cantidad');

                $table->integer('stock_anterior');

                $table->integer('stock_nuevo');

                /*
                |--------------------------------------------------------------------------
                | COSTOS
                |--------------------------------------------------------------------------
                */

                $table->decimal(
                    'costo_unitario',
                    10,
                    2
                )->default(0);

                $table->decimal(
                    'costo_total',
                    10,
                    2
                )->default(0);

                /*
                |--------------------------------------------------------------------------
                | OBSERVACIÓN
                |--------------------------------------------------------------------------
                */

                $table->text('descripcion')
                    ->nullable();

                $table->timestamps();
            }

        );
    }

    public function down(): void
    {

        Schema::dropIfExists(
            'movimiento_inventarios'
        );
    }
};