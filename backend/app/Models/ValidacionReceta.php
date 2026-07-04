<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValidacionReceta extends Model
{
    protected $table =

        'validaciones_receta';

    protected $fillable = [

        'cliente_id',

        'quimico_id',

        'nombre_medico',

        'cmp_medico',

        'indicaciones',

        'observaciones',

        'fecha_receta',

        'estado',

        'usado'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    public function cliente()
    {

        return $this->belongsTo(

            Cliente::class
        );
    }

    public function quimico()
    {

        return $this->belongsTo(

            User::class,

            'quimico_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DETALLES (PRODUCTOS SOLICITADOS, VIA DETALLE_VENTAS)
    |--------------------------------------------------------------------------
    */

    public function detalles()
    {

        return $this->hasMany(

            DetalleVenta::class
        );
    }
}