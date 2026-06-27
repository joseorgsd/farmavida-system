<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {

    $table->id();

    // Código único del producto
    $table->string('codigo')->unique();

    // Nombre medicamento
    $table->string('nombre');

    // Descripción
    $table->text('descripcion')->nullable();

    // Precio compra
    $table->decimal('precio_compra', 10, 2);

    // Precio venta
    $table->decimal('precio_venta', 10, 2);

    // Stock actual
    $table->integer('stock')->default(0);

    // Stock mínimo alerta
    $table->integer('stock_minimo')->default(5);

    // Lote farmacéutico
    $table->string('lote')->nullable();

    // Fecha vencimiento
    $table->date('fecha_vencimiento')->nullable();

    // Código de barras
    $table->string('codigo_barras')->nullable();

    // Imagen producto
    $table->string('imagen')->nullable();

    // Estado activo/inactivo
    $table->boolean('estado')->default(true);

    // Usuario creador
    $table->foreignId('user_id')
          ->nullable()
          ->constrained()
          ->nullOnDelete();
    $table->softDeletes();
    $table->timestamps();

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
