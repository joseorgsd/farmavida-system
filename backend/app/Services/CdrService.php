<?php

namespace App\Services;

class CdrService
{

    public function procesar($response)
    {

        /*
        |--------------------------------------------------------------------------
        | SIMULACIÓN CDR
        |--------------------------------------------------------------------------
        |
        | Más adelante:
        |
        | - leer ZIP CDR
        | - extraer XML
        | - parsear SUNAT
        |
        */

        return [

            'codigo' => '0',

            'estado' => 'ACEPTADO',

            'descripcion' =>

                'La factura fue aceptada por SUNAT',

            'hash' => sha1(time())

        ];
    }
}