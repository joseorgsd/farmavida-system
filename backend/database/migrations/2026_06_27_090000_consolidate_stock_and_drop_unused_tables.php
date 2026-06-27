<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Migrar datos: si stock_unidades quedó en 0 pero stock tiene valor real,
        //    nos quedamos con el dato de stock antes de borrarlo.
        DB::statement('UPDATE productos SET stock_unidades = stock WHERE stock_unidades = 0 AND stock > 0');

        // 2. Eliminar columna duplicada en productos
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('stock');
        });

        // 3. Eliminar columna muerta en detalle_ventas
        Schema::table('detalle_ventas', function (Blueprint $table) {
            $table->dropColumn('precio');
        });

        // 4. Eliminar tablas no usadas por ningún Controller/Model
        Schema::dropIfExists('producto_presentaciones');
        Schema::dropIfExists('productos_restringidos');
        Schema::dropIfExists('movimientos_stock');
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->integer('stock')->default(0);
        });

        Schema::table('detalle_ventas', function (Blueprint $table) {
            $table->decimal('precio', 10, 2)->nullable();
        });

        Schema::create('producto_presentaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained();
            $table->foreignId('unidad_medida_id')->constrained('unidades_medida');
            $table->integer('factor');
            $table->decimal('precio', 10, 2);
            $table->boolean('es_principal')->default(false);
            $table->timestamps();
        });

        Schema::create('productos_restringidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained();
            $table->boolean('requiere_receta')->default(false);
            $table->boolean('requiere_dni')->default(false);
            $table->boolean('requiere_medico')->default(false);
            $table->boolean('controlado')->default(false);
            $table->text('observacion')->nullable();
            $table->timestamps();
        });

        Schema::create('movimientos_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained();
            $table->enum('tipo', ['ENTRADA', 'SALIDA']);
            $table->string('motivo');
            $table->integer('cantidad');
            $table->integer('stock_anterior');
            $table->integer('stock_nuevo');
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }
};