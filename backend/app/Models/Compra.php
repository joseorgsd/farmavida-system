<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{

    protected $fillable = [

        'numero_compra',

        'proveedor_id',

        'user_id',

        'subtotal',

        'igv',

        'total'
    ];

    /*
    |--------------------------------------------------------------------------
    | DETALLES
    |--------------------------------------------------------------------------
    */

    public function detalles()
    {

        return $this->hasMany(
            DetalleCompra::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PROVEEDOR
    |--------------------------------------------------------------------------
    */

    public function proveedor()
    {

        return $this->belongsTo(
            Proveedor::class
        );
    }
}