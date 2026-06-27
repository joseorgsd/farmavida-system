<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoRestringido extends Model
{

    protected $table =
        'productos_restringidos';

    protected $fillable = [

        'producto_id',

        'requiere_receta',

        'requiere_dni',

        'requiere_medico',

        'controlado',

        'observacion'
    ];
}