<?php

namespace App\Services;

class FirmaDigitalService
{

    public function firmar(
        $xmlPath,
        $xmlFirmadoPath
    )
    {

        /*
        |--------------------------------------------------------------------------
        | FIRMA SIMULADA
        |--------------------------------------------------------------------------
        |
        | En producción:
        | - OpenSSL
        | - Certificado real
        | - SHA256
        |
        */

        copy($xmlPath, $xmlFirmadoPath);

        return true;
    }
}