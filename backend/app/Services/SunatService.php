<?php

namespace App\Services;

use SoapClient;

class SunatService
{

    public function enviar($zipPath)
    {

        try {

            $client = new SoapClient(

                null,

                [

                    'location' =>
                        config('sunat.soap_url'),

                    'uri' =>
                        'http://service.sunat.gob.pe',

                    'trace' => 1,

                ]

            );

            /*
            |--------------------------------------------------------------------------
            | BASE64 ZIP
            |--------------------------------------------------------------------------
            */

            $zipContent = file_get_contents(
                $zipPath
            );

            $params = [

                'fileName' =>
                    basename($zipPath),

                'contentFile' =>
                    base64_encode($zipContent)

            ];

            /*
            |--------------------------------------------------------------------------
            | ENVÍO SOAP
            |--------------------------------------------------------------------------
            */

            $response = $client->__soapCall(

                'sendBill',

                [$params]

            );

            return [

                'success' => true,

                'response' => $response

            ];

        } catch (\Exception $e) {

            return [

                'success' => false,

                'message' => $e->getMessage()

            ];
        }
    }
}