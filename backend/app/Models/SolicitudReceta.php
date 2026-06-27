<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudReceta extends Model
{

    protected $table =
        'solicitudes_receta';

    protected $fillable = [

        'cliente_id',

        'producto_id',

        'cajero_id',

        'quimico_id',

        'cantidad_solicitada',

        'cantidad_aprobada',

        'estado',

        'numero_receta',

        'medico',

        'observacion'
    ];

    /*
    |--------------------------------------------------------------------------
    | CLIENTE
    |--------------------------------------------------------------------------
    */

    public function cliente()
    {

        return $this->belongsTo(
            Cliente::class
        );
    }

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
    | CAJERO
    |--------------------------------------------------------------------------
    */

    public function cajero()
    {

        return $this->belongsTo(
            User::class,
            'cajero_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | QUIMICO
    |--------------------------------------------------------------------------
    */

    public function quimico()
    {

        return $this->belongsTo(
            User::class,
            'quimico_id'
        );
    }
}