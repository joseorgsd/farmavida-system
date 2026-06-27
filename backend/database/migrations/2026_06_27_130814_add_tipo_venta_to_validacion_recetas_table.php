<?php

use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {

        Schema::table(

            'validaciones_receta',

            function (Blueprint $table) {

                $table->string(

                    'tipo_venta'

                )->default('CAJA');
            }
        );
    }

    public function down(): void
    {

        Schema::table(

            'validaciones_receta',

            function (Blueprint $table) {

                $table->dropColumn(

                    'tipo_venta'
                );
            }
        );
    }
};
