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
        Schema::table('ventas', function (Blueprint $table) {

            if (!Schema::hasColumn('ventas', 'forma_pago')) {

                $table->enum('forma_pago', [

                    'EFECTIVO',

                    'YAPE',

                    'PLIN',

                    'TARJETA'

                ])->default('EFECTIVO')->after('tipo_comprobante');
            }

            if (!Schema::hasColumn('ventas', 'monto_pagado')) {

                $table->decimal('monto_pagado', 10, 2)

                    ->nullable()

                    ->after('total');
            }

            if (!Schema::hasColumn('ventas', 'vuelto')) {

                $table->decimal('vuelto', 10, 2)

                    ->default(0)

                    ->after('monto_pagado');
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
        Schema::table('ventas', function (Blueprint $table) {

            if (Schema::hasColumn('ventas', 'forma_pago')) {
                $table->dropColumn('forma_pago');
            }

            if (Schema::hasColumn('ventas', 'monto_pagado')) {
                $table->dropColumn('monto_pagado');
            }

            if (Schema::hasColumn('ventas', 'vuelto')) {
                $table->dropColumn('vuelto');
            }
        });
    }
};