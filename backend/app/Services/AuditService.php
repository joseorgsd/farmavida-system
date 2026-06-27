<?php

namespace App\Services;

use App\Models\AuditLog;

use Illuminate\Support\Facades\Auth;

class AuditService
{

    /*
    |--------------------------------------------------------------------------
    | REGISTRAR EVENTO
    |--------------------------------------------------------------------------
    */

    public static function log(

        $accion,

        $modulo,

        $descripcion = null,

        $data = []

    )
    {

        AuditLog::create([

            'user_id' =>

                Auth::id(),

            'accion' =>

                $accion,

            'modulo' =>

                $modulo,

            'descripcion' =>

                $descripcion,

            'ip_address' =>

                request()->ip(),

            'user_agent' =>

                request()->userAgent(),

            'data' =>

                $data

        ]);
    }
}