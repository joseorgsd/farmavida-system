<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detalle_ventas', function (Blueprint $table) {

            if (!Schema::hasColumn('detalle_ventas', 'precio_unitario')) {
                $table->decimal('precio_unitario', 10, 2)->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('detalle_ventas', function (Blueprint $table) {
            $table->dropColumn('precio_unitario');
        });
    }
};