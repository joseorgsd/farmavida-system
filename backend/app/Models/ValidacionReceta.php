<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValidacionReceta extends Model
{
    protected $table =

        'validaciones_receta';

    protected $fillable = [

        'cliente_id',

        'producto_id',

        'quimico_id',

        'cantidad_aprobada',
        
        'tipo_venta',

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

    public function producto()
    {

        return $this->belongsTo(

            Producto::class
        );
    }

    public function quimico()
    {

        return $this->belongsTo(

            User::class,

            'quimico_id'
        );
    }
}