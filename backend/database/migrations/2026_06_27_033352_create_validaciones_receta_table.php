<?php

use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('validaciones_receta', function (

            Blueprint $table

        ) {

            $table->id();

            $table->foreignId(

                'cliente_id'

            )->constrained();

            $table->foreignId(

                'producto_id'

            )->constrained();

            $table->foreignId(

                'quimico_id'

            )->constrained('users');

            $table->integer(

                'cantidad_aprobada'
            );

            $table->date(

                'fecha_receta'
            );

            $table->boolean(

                'usado'
            )->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {

        Schema::dropIfExists(

            'validaciones_receta'
        );
    }
};