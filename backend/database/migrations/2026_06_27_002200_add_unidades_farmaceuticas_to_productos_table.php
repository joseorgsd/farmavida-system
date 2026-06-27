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
            | FARMACIA
            |--------------------------------------------------------------------------
            */

            $table->integer('unidades_por_blister')->default(1);
            $table->integer('blisters_por_caja')->default(1);

            /*
            |--------------------------------------------------------------------------
            | RESTRINGIDOS
            |--------------------------------------------------------------------------
            */

            $table->boolean('requiere_receta')->default(false);
            $table->boolean('producto_controlado')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn([
                'unidades_por_blister',
                'blisters_por_caja',
                'requiere_receta',
                'producto_controlado',
            ]);
        });
    }
};