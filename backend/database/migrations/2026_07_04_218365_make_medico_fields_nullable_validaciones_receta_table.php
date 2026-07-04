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

            if (Schema::hasColumn('validaciones_receta', 'quimico_id')) {
                $table->unsignedBigInteger('quimico_id')->nullable()->change();
            }

            if (Schema::hasColumn('validaciones_receta', 'nombre_medico')) {
                $table->string('nombre_medico')->nullable()->change();
            }

            if (Schema::hasColumn('validaciones_receta', 'cmp_medico')) {
                $table->string('cmp_medico')->nullable()->change();
            }

            if (Schema::hasColumn('validaciones_receta', 'fecha_receta')) {
                $table->date('fecha_receta')->nullable()->change();
            }

            if (Schema::hasColumn('validaciones_receta', 'indicaciones')) {
                $table->text('indicaciones')->nullable()->change();
            }

            if (Schema::hasColumn('validaciones_receta', 'observaciones')) {
                $table->text('observaciones')->nullable()->change();
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