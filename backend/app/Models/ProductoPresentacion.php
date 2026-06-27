<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoPresentacion extends Model
{

    protected $fillable = [

        'producto_id',

        'unidad_medida_id',

        'factor',

        'precio',

        'es_principal'
    ];

    /*
    |--------------------------------------------------------------------------
    | PRODUCTO
    |--------------------------------------------------------------------------
    */

    public function producto()
    {

        return $this->belongsTo(
            Producto::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UNIDAD
    |--------------------------------------------------------------------------
    */

    public function unidad()
    {

        return $this->belongsTo(
            UnidadMedida::class,
            'unidad_medida_id'
        );
    }
}