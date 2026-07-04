<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
    |--------------------------------------------------------------------------
    | UP
    |--------------------------------------------------------------------------
    */

    public function up(): void
    {
        Schema::table('detalle_ventas', function (Blueprint $table) {

            if (!Schema::hasColumn('detalle_ventas', 'validacion_receta_id')) {

                $table->foreignId('validacion_receta_id')
                    ->nullable()
                    ->after('venta_id')
                    ->constrained('validaciones_receta')
                    ->nullOnDelete();
            }
        });

        Schema::table('detalle_ventas', function (Blueprint $table) {

            // Un detalle puede existir ANTES de tener una venta real
            // (mientras está pendiente de validación de receta).

            if (Schema::hasColumn('detalle_ventas', 'venta_id')) {
                $table->unsignedBigInteger('venta_id')->nullable()->change();
            }

            if (Schema::hasColumn('detalle_ventas', 'precio_unitario')) {
                $table->decimal('precio_unitario', 10, 2)->nullable()->change();
            }

            if (Schema::hasColumn('detalle_ventas', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->nullable()->change();
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | DOWN
    |--------------------------------------------------------------------------
    */

    public function down(): void
    {
        Schema::table('detalle_ventas', function (Blueprint $table) {

            if (Schema::hasColumn('detalle_ventas', 'validacion_receta_id')) {
                $table->dropConstrainedForeignId('validacion_receta_id');
            }
        });
    }
};