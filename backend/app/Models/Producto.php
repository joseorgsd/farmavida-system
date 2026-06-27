<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{

    protected $table = 'productos';

    protected $fillable = [

        'nombre',

        
        'stock_unidades',

        'precio_compra',

        'precio_venta',

        'precio_blister',

        'precio_unidad',

        'blisters_por_caja',

        'unidades_por_blister',

        'requiere_receta'
    ];
}
