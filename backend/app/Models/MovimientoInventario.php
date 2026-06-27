<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoInventario extends Model
{

    protected $fillable = [

        'producto_id',

        'user_id',

        'tipo_movimiento',

        'cantidad',

        'stock_anterior',

        'stock_nuevo',

        'costo_unitario',

        'costo_total',

        'descripcion'

    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    public function producto()
    {

        return $this->belongsTo(
            Producto::class
        );
    }

    public function user()
    {

        return $this->belongsTo(
            User::class
        );
    }
}