<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\ValidacionReceta;

use App\Models\DetalleVenta;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

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

                'quimico',

                'detalles.producto'

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
    | DERIVAR CLIENTE (CAJERO: SOLO CLIENTE + PRODUCTOS)
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {

        $request->validate([

            'cliente_id' =>

                'required|exists:clientes,id',

            'productos' =>

                'required|array|min:1',

            'productos.*.producto_id' =>

                'required|exists:productos,id',

            'productos.*.cantidad' =>

                'required|numeric|min:1',

            'productos.*.tipo_venta' =>

                'required|in:CAJA,BLISTER,UNIDAD',
        ], [

            'cliente_id.required' =>

                'Debe seleccionar un cliente',

            'cliente_id.exists' =>

                'El cliente seleccionado no existe',

    'productos.required' =>

        'Debe agregar al menos un producto',

    'productos.min' =>

        'Debe agregar al menos un producto',

    'productos.*.producto_id.required' =>

        'Debe seleccionar un producto',

    'productos.*.producto_id.exists' =>

        'Uno de los productos seleccionados no existe',

    'productos.*.cantidad.required' =>

        'Debe indicar la cantidad',

            'productos.*.cantidad.numeric' =>

        'La cantidad debe ser un número',

            'productos.*.cantidad.min' =>

        'La cantidad debe ser mayor a 0',

            'productos.*.tipo_venta.required' =>

        'Debe seleccionar el tipo de venta',

            'productos.*.tipo_venta.in' =>

                'El tipo de venta debe ser CAJA, BLISTER o UNIDAD'
        ]);


        $validacion = DB::transaction(function () use ($request) {

            $validacion = ValidacionReceta::create([

                'cliente_id' =>

                    $request->cliente_id,

                'producto_id' => null,

                'quimico_id' => null,

                'nombre_medico' => null,

                'cmp_medico' => null,

                'indicaciones' => null,

                'observaciones' => null,

                'fecha_receta' => null,

                'estado' =>

                    'PENDIENTE',

                'usado' => false
            ]);

            foreach ($request->productos as $item) {

                DetalleVenta::create([

                    'venta_id' => null,

                    'validacion_receta_id' =>

                        $validacion->id,

                    'producto_id' =>

                        $item['producto_id'],

                    'tipo_venta' =>

                        $item['tipo_venta'],

                    'cantidad' =>

                        $item['cantidad'],

                    'precio_unitario' => null,

                    'subtotal' => null,
                ]);
            }

            return $validacion;
        });

        return response()->json([

            'success' => true,

            'message' =>

                'Cliente derivado al químico farmacéutico',

            'data' =>

                $validacion->load('detalles.producto')
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR DATOS MEDICOS (QUIMICO)
    |--------------------------------------------------------------------------
    */

    public function actualizarDatos(Request $request, int $id)
    {

        $validacion =

            ValidacionReceta::findOrFail($id);

        if ($validacion->estado !== 'PENDIENTE') {

            return response()->json([

                'success' => false,

                'message' =>

                    'Solo se pueden editar validaciones pendientes'
            ], 403);
        }

        $request->validate([

            'nombre_medico' =>

                'required|string|max:255',

            'cmp_medico' =>

                'required|string|max:255',

            'fecha_receta' =>

                'required|date',

            'indicaciones' =>

            'nullable|string',

            'observaciones' =>

                'nullable|string',
        ], [

            'nombre_medico.required' =>

                'Debe ingresar el nombre del médico',

            'cmp_medico.required' =>

                'Debe ingresar el CMP del médico',

            'fecha_receta.required' =>

        'Debe ingresar la fecha de la receta',

            'fecha_receta.date' =>

                'La fecha de la receta no es válida'
        ]);
        $validacion->nombre_medico =

            $request->nombre_medico;

        $validacion->cmp_medico =

            $request->cmp_medico;

        $validacion->fecha_receta =

            $request->fecha_receta;

        $validacion->indicaciones =

            $request->indicaciones;

        $validacion->observaciones =

            $request->observaciones;

        $validacion->quimico_id =

            Auth::id() ?? 1;

        $validacion->save();

        return response()->json([

            'success' => true,

            'message' =>

                'Datos del médico actualizados',

            'data' =>

                $validacion->load('detalles.producto')
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | AGREGAR PRODUCTO (QUIMICO)
    |--------------------------------------------------------------------------
    */

    public function agregarProducto(Request $request, int $id)
    {

        $validacion =

            ValidacionReceta::findOrFail($id);

        if ($validacion->estado !== 'PENDIENTE') {

            return response()->json([

                'success' => false,

                'message' =>

                    'Solo se pueden agregar productos a validaciones pendientes'
            ], 403);
        }

        $request->validate([

            'producto_id' =>

                'required|exists:productos,id',

            'cantidad' =>

                'required|numeric|min:1',

            'tipo_venta' =>

                'required|in:CAJA,BLISTER,UNIDAD',
            ], [

                'producto_id.required' =>

                    'Debe seleccionar un producto',

                'producto_id.exists' =>

            'El producto seleccionado no existe',

        'cantidad.required' =>

            'Debe indicar la cantidad',

        'cantidad.min' =>

            'La cantidad debe ser mayor a 0',

        'tipo_venta.required' =>

            'Debe seleccionar el tipo de venta',

        'tipo_venta.in' =>

            'El tipo de venta debe ser CAJA, BLISTER o UNIDAD'
        ]);
        $detalle = DetalleVenta::create([

            'venta_id' => null,

            'validacion_receta_id' =>

                $validacion->id,

            'producto_id' =>

                $request->producto_id,

            'tipo_venta' =>

                $request->tipo_venta,

            'cantidad' =>

                $request->cantidad,

            'precio_unitario' => null,

            'subtotal' => null,
        ]);

        return response()->json([

            'success' => true,

            'message' =>

                'Producto agregado',

            'data' =>

                $detalle->load('producto')
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | QUITAR PRODUCTO (QUIMICO)
    |--------------------------------------------------------------------------
    */

    public function quitarProducto(int $id, int $detalleId)
    {

        $validacion =

            ValidacionReceta::findOrFail($id);

        if ($validacion->estado !== 'PENDIENTE') {

            return response()->json([

                'success' => false,

                'message' =>

                    'Solo se pueden quitar productos de validaciones pendientes'
            ], 403);
        }

        $detalle =

            DetalleVenta::where(

                'validacion_receta_id',

                $id
            )

            ->findOrFail($detalleId);

        $detalle->delete();

        return response()->json([

            'success' => true,

            'message' =>

                'Producto quitado'
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

            ValidacionReceta::with('detalles')

            ->findOrFail($id);

        if (

            !$validacion->nombre_medico

            ||

            !$validacion->cmp_medico
        ) {

            return response()->json([

                'success' => false,

                'message' =>

                    'Debe completar los datos del médico antes de aprobar'
            ], 422);
        }

        if ($validacion->detalles->isEmpty()) {

            return response()->json([

                'success' => false,

                'message' =>

                    'La validación no tiene productos'
            ], 422);
        }

        $validacion->estado =

            'APROBADO';

        $validacion->quimico_id =

            Auth::id() ?? 1;

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

        $validacion->quimico_id =

            Auth::id() ?? 1;

        $validacion->save();

        return response()->json([

            'success' => true,

            'message' =>

                'Receta rechazada'
        ]);
    }
    /*
|--------------------------------------------------------------------------
| PENDIENTES DE VENTA (APROBADAS, NO USADAS)
|--------------------------------------------------------------------------
*/

public function pendientesVenta()
{

    $validaciones =

        ValidacionReceta::with([

            'cliente',

            'detalles.producto'

        ])

        ->where('estado', 'APROBADO')

        ->where('usado', false)

        ->latest()

        ->get();

    return response()->json([

        'success' => true,

        'data' => $validaciones
    ]);
    }
}