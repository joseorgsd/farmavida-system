<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\ValidacionReceta;

use Illuminate\Support\Facades\Auth;
class ValidacionRecetaController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | LISTAR
    |--------------------------------------------------------------------------
    */

    public function index()
    {

        $validaciones =

            ValidacionReceta::with([

                'cliente',

                'producto',

                'quimico'

            ])

            ->latest()

            ->get();

        return response()->json([

            'success' => true,

            'data' => $validaciones
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CREAR SOLICITUD
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {

        $request->validate([

            'cliente_id' =>

                'required|exists:clientes,id',

            'producto_id' =>

                'required|exists:productos,id',

            'cantidad_aprobada' =>

                'required|numeric|min:1',
            'tipo_venta' => 'required|in:CAJA,BLISTER,UNIDAD',
        ]);

        $validacion = ValidacionReceta::create([

            'cliente_id' =>

                $request->cliente_id,

            'producto_id' =>

                $request->producto_id,

            'quimico_id' =>

                Auth::id() ?? 1,

            'cantidad_aprobada' =>

                $request->cantidad_aprobada,
            'tipo_venta' => $request->tipo_venta,

            'fecha_receta' =>

                now(),

            'estado' =>

                'PENDIENTE',

            'usado' => false
        ]);

        return response()->json([

            'success' => true,

            'message' =>

                'Solicitud creada',

            'data' => $validacion
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | APROBAR
    |--------------------------------------------------------------------------
    */

    public function aprobar(int $id)
    {

        $validacion =

            ValidacionReceta::findOrFail($id);

        $validacion->estado =

            'APROBADO';

        $validacion->save();

        return response()->json([

            'success' => true,

            'message' =>

                'Receta aprobada'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | RECHAZAR
    |--------------------------------------------------------------------------
    */

    public function rechazar(int $id)
    {

        $validacion =

            ValidacionReceta::findOrFail($id);

        $validacion->estado =

            'RECHAZADO';

        $validacion->save();

        return response()->json([

            'success' => true,

            'message' =>

                'Receta rechazada'
        ]);
    }
}