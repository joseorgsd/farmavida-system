<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        Producto::create([
            'codigo' => 'MED-001',
            'nombre' => 'Paracetamol',
            'precio_compra' => 10,
            'precio_venta' => 15,
            'precio_blister' => 2,
            'precio_unidad' => 0.50,
            'stock_unidades' => 1000,
            'blisters_por_caja' => 10,
            'unidades_por_blister' => 10,
            'requiere_receta' => false,
        ]);

        Producto::create([
            'codigo' => 'MED-002',
            'nombre' => 'Amoxicilina',
            'precio_compra' => 20,
            'precio_venta' => 30,
            'precio_blister' => 5,
            'precio_unidad' => 1,
            'stock_unidades' => 500,
            'blisters_por_caja' => 10,
            'unidades_por_blister' => 10,
            'requiere_receta' => true,
        ]);
    }
}