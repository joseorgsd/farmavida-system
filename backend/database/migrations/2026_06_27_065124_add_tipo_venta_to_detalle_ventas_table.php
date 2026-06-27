<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detalle_ventas', function (Blueprint $table) {

            if (!Schema::hasColumn('detalle_ventas', 'tipo_venta')) {
                $table->string('tipo_venta')->nullable()->after('producto_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('detalle_ventas', function (Blueprint $table) {
            if (Schema::hasColumn('detalle_ventas', 'tipo_venta')) {
                $table->dropColumn('tipo_venta');
            }
        });
    }
};