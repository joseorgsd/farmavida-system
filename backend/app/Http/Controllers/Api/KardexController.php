<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\MovimientoInventario;

class KardexController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | HISTORIAL INVENTARIO
    |--------------------------------------------------------------------------
    */

    public function index()
    {

        $movimientos = MovimientoInventario::with([

            'producto',

            'user'

        ])

        ->latest()

        ->paginate(20);

        return response()->json(
            $movimientos
        );
    }
}