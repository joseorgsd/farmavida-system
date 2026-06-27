
<?php

use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('detalle_ventas', function (Blueprint $table) {
            if (Schema::hasColumn('detalle_ventas', 'precio')) {
                $table->dropColumn('precio');
            }
        });
    }

    public function down(): void
    {

        Schema::table(

            'detalle_ventas',

            function (Blueprint $table) {

                $table->decimal(

                    'precio',

                    10,

                    2

                )->default(0);
            }
        );
    }
};