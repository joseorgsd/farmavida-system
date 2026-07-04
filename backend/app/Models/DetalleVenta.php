<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{

    protected $table = 'detalle_ventas';

    protected $fillable = [

        'venta_id',

        'validacion_receta_id',

        'producto_id',

        'tipo_venta',

        'cantidad',

        'precio_unitario',

        'subtotal'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    public function venta()
    {

        return $this->belongsTo(

            Venta::class
        );
    }

    public function producto()
    {

        return $this->belongsTo(

            Producto::class
        );
    }

    public function validacionReceta()
    {

        return $this->belongsTo(

            ValidacionReceta::class
        );
    }
}