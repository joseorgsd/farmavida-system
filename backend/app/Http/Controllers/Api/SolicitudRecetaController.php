<?php

namespace App\Http\Controllers\Api;

use App\Models\SolicitudReceta;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

class SolicitudRecetaController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | LISTAR
    |--------------------------------------------------------------------------
    */

    public function index()
    {

        return response()->json(

            SolicitudReceta::with(

                'cliente',

                'producto',

                'cajero',

                'quimico'

            )->latest()->get()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREAR SOLICITUD
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request
    )
    {

        $request->validate([

            'cliente_id' =>

                'required|exists:clientes,id',

            'producto_id' =>

                'required|exists:productos,id',

            'cantidad_solicitada' =>

                'required|integer|min:1'
        ]);

        $solicitud =
            SolicitudReceta::create([

                'cliente_id' =>

                    $request->cliente_id,

                'producto_id' =>

                    $request->producto_id,

                'cajero_id' =>

                    Auth::id(),

                'cantidad_solicitada' =>

                    $request->cantidad_solicitada,

                'estado' =>

                    'PENDIENTE'
            ]);

        return response()->json([

            'success' => true,

            'message' =>

                'Solicitud enviada al químico',

            'data' =>

                $solicitud
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | APROBAR
    |--------------------------------------------------------------------------
    */

    public function aprobar(
        Request $request,
        $id
    )
    {

        $request->validate([

            'cantidad_aprobada' =>

                'required|integer|min:1',

            'numero_receta' =>

                'required',

            'medico' =>

                'required'
        ]);

        $solicitud =
            SolicitudReceta::findOrFail($id);

        $solicitud->update([

            'quimico_id' =>

                Auth::id(),

            'cantidad_aprobada' =>

                $request->cantidad_aprobada,

            'numero_receta' =>

                $request->numero_receta,

            'medico' =>

                $request->medico,

            'estado' =>

                'APROBADO'
        ]);

        return response()->json([

            'success' => true,

            'message' =>

                'Solicitud aprobada'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | RECHAZAR
    |--------------------------------------------------------------------------
    */

    public function rechazar(
        Request $request,
        $id
    )
    {

        $solicitud =
            SolicitudReceta::findOrFail($id);

        $solicitud->update([

            'quimico_id' =>

                Auth::id(),

            'estado' =>

                'RECHAZADO',

            'observacion' =>

                $request->observacion
        ]);

        return response()->json([

            'success' => true,

            'message' =>

                'Solicitud rechazada'
        ]);
    }
}