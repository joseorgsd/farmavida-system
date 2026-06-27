<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {

            if (!Schema::hasColumn('clientes', 'tipo_documento')) {
                $table->string('tipo_documento')->default('DNI');
            }

            if (!Schema::hasColumn('clientes', 'direccion')) {
                $table->string('direccion')->nullable();
            }

            if (!Schema::hasColumn('clientes', 'telefono')) {
                $table->string('telefono')->nullable();
            }

            $table->string('dni')->change();
        });

        // SQL directo para garantizar que email quede NULL,
        // evitando ambigüedades de Doctrine al combinar varios change() juntos.
        DB::statement('ALTER TABLE clientes MODIFY `email` VARCHAR(255) NULL DEFAULT NULL');
    }

    public function down(): void
    {
        //
    }
};