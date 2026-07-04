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
        Schema::table('validaciones_receta', function (Blueprint $table) {

            if (Schema::hasColumn('validaciones_receta', 'producto_id')) {
                $table->unsignedBigInteger('producto_id')->nullable()->change();
            }

            if (Schema::hasColumn('validaciones_receta', 'cantidad_solicitada')) {
                $table->integer('cantidad_solicitada')->nullable()->change();
            }

            if (Schema::hasColumn('validaciones_receta', 'tipo_venta_solicitada')) {
                $table->string('tipo_venta_solicitada')->nullable()->change();
            }

            if (Schema::hasColumn('validaciones_receta', 'cantidad_aprobada')) {
                $table->integer('cantidad_aprobada')->nullable()->change();
            }

            if (Schema::hasColumn('validaciones_receta', 'tipo_venta_aprobada')) {
                $table->string('tipo_venta_aprobada')->nullable()->change();
            }

            if (Schema::hasColumn('validaciones_receta', 'tipo_venta')) {
                $table->string('tipo_venta')->nullable()->change();
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
        // No revertimos a NOT NULL para evitar romper datos existentes.
    }
};