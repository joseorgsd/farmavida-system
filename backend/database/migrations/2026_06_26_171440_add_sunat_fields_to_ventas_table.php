<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::table('ventas', function (
            Blueprint $table
        ) {

            $table->string(
                'sunat_estado'
            )->nullable();

            $table->text(
                'sunat_descripcion'
            )->nullable();

            $table->string(
                'sunat_codigo'
            )->nullable();

            $table->string(
                'sunat_hash'
            )->nullable();

            $table->timestamp(
                'sunat_enviado_en'
            )->nullable();

        });
    }

    public function down(): void
    {

        Schema::table('ventas', function (
            Blueprint $table
        ) {

            $table->dropColumn([

                'sunat_estado',

                'sunat_descripcion',

                'sunat_codigo',

                'sunat_hash',

                'sunat_enviado_en'

            ]);
        });
    }
};