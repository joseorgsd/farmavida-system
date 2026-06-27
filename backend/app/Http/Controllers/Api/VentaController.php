<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\ValidacionReceta;

class VentaController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | LISTAR VENTAS
    |--------------------------------------------------------------------------
    */

    public function index()
    {

        $ventas = Venta::with([

            'cliente',

            'usuario'
        ])
        ->latest()
        ->get();

        return response()->json([

            'success' => true,

            'data' => $ventas
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTRAR VENTA
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {

        /*
        |--------------------------------------------------------------------------
        | VALIDACIONES
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'cliente_id' =>

                'required|exists:clientes,id',

            'tipo_comprobante' =>

                'required|string',

            'productos' =>

                'required|array|min:1',

            'productos.*.producto_id' =>

                'required|exists:productos,id',

            'productos.*.cantidad' =>

                'required|numeric|min:1',

            'productos.*.tipo_venta' =>

                'required|string'
        ]);

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | TOTAL
            |--------------------------------------------------------------------------
            */

            $total = 0;

            foreach (

                $request->productos

                as $item

            ) {

                $producto = Producto::findOrFail(

                    $item['producto_id']
                );

                /*
                |--------------------------------------------------------------------------
                | PRECIO SEGÚN TIPO
                |--------------------------------------------------------------------------
                */

                $precio = 0;

                if (

                    $item['tipo_venta']

                    === 'CAJA'
                ) {

                    $precio =

                        $producto->precio_venta;
                }

                elseif (

                    $item['tipo_venta']

                    === 'BLISTER'
                ) {

                    $precio =

                        $producto->precio_blister;
                }

                else {

                    $precio =

                        $producto->precio_unidad;
                }

                /*
                |--------------------------------------------------------------------------
                | CONVERSIÓN A UNIDADES
                |--------------------------------------------------------------------------
                */

                $cantidadDescontar = 0;

                if (

                    $item['tipo_venta']

                    === 'CAJA'
                ) {

                    $cantidadDescontar =

                        $item['cantidad']

                        *

                        $producto->blisters_por_caja

                        *

                        $producto->unidades_por_blister;
                }

                elseif (

                    $item['tipo_venta']

                    === 'BLISTER'
                ) {

                    $cantidadDescontar =

                        $item['cantidad']

                        *

                        $producto->unidades_por_blister;
                }

                else {

                    $cantidadDescontar =

                        $item['cantidad'];
                }

                /*
                |--------------------------------------------------------------------------
                | VALIDAR STOCK
                |--------------------------------------------------------------------------
                */

                if (

                    $producto->stock_unidades

                    <

                    $cantidadDescontar
                ) {

                    return response()->json([

                        'success' => false,

                        'message' =>

                            'Stock insuficiente para '

                            .

                            $producto->nombre
                    ], 400);
                }

                /*
                |--------------------------------------------------------------------------
                | VALIDACIÓN RECETA
                |--------------------------------------------------------------------------
                */

                if (

                    $producto->requiere_receta
                ) {

                    $validacion =

                        ValidacionReceta::where(

                            'cliente_id',

                            $request->cliente_id
                        )

                        ->where(

                            'producto_id',

                            $producto->id
                        )

                        ->where(

                            'tipo_venta',

                            $item['tipo_venta']
                        )

                        ->where(

                            'estado',

                            'APROBADO'
                        )

                        ->where(

                            'usado',

                            false
                        )

                        ->first();

                    if (!$validacion) {

                        return response()->json([

                            'success' => false,

                            'message' =>

                                'Producto requiere validación del químico farmacéutico'
                        ], 403);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | VALIDAR CANTIDAD
                    |--------------------------------------------------------------------------
                    */

                    if (

                        $item['cantidad']

                        >

                        $validacion->cantidad_aprobada
                    ) {

                        return response()->json([

                            'success' => false,

                            'message' =>

                                'Cantidad excede receta aprobada'
                        ], 403);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | MARCAR USADO
                    |--------------------------------------------------------------------------
                    */

                    $validacion->usado = true;

                    $validacion->save();
                }

                /*
                |--------------------------------------------------------------------------
                | SUBTOTAL
                |--------------------------------------------------------------------------
                */

                $subtotalProducto =

                    $precio

                    *

                    $item['cantidad'];

                $total += $subtotalProducto;
            }

            /*
            |--------------------------------------------------------------------------
            | SUBTOTAL / IGV
            |--------------------------------------------------------------------------
            */

            $subtotal =

                $total / 1.18;

            $igv =

                $total - $subtotal;

            /*
            |--------------------------------------------------------------------------
            | CREAR VENTA
            |--------------------------------------------------------------------------
            */

            $venta = Venta::create([

                'numero_venta' =>

                    'V-' . time(),

                'cliente_id' =>

                    $request->cliente_id,

                'user_id' =>

                    Auth::id() ?? 1,

                'tipo_comprobante' =>

                    $request->tipo_comprobante,

                'subtotal' =>

                    round(
                        $subtotal,
                        2
                    ),

                'igv' =>

                    round(
                        $igv,
                        2
                    ),

                'total' =>

                    round(
                        $total,
                        2
                    )
            ]);

            /*
            |--------------------------------------------------------------------------
            | DETALLE VENTA
            |--------------------------------------------------------------------------
            */

            foreach (

                $request->productos

                as $item

            ) {

                $producto = Producto::findOrFail(

                    $item['producto_id']
                );

                /*
                |--------------------------------------------------------------------------
                | PRECIO
                |--------------------------------------------------------------------------
                */

                $precio = 0;

                if (

                    $item['tipo_venta']

                    === 'CAJA'
                ) {

                    $precio =

                        $producto->precio_venta;
                }

                elseif (

                    $item['tipo_venta']

                    === 'BLISTER'
                ) {

                    $precio =

                        $producto->precio_blister;
                }

                else {

                    $precio =

                        $producto->precio_unidad;
                }

                /*
                |--------------------------------------------------------------------------
                | SUBTOTAL DETALLE
                |--------------------------------------------------------------------------
                */

                $subtotalDetalle =

                    $precio

                    *

                    $item['cantidad'];

                /*
                |--------------------------------------------------------------------------
                | CREAR DETALLE
                |--------------------------------------------------------------------------
                */

                DetalleVenta::create([

                    'venta_id' =>

                        $venta->id,

                    'producto_id' =>

                        $producto->id,

                    'tipo_venta' =>

                        $item['tipo_venta'],

                    'cantidad' =>

                        $item['cantidad'],

                    'precio_unitario' =>

                        $precio,

                    'subtotal' =>

                        $subtotalDetalle
                ]);

                /*
                |--------------------------------------------------------------------------
                | CONVERSIÓN STOCK
                |--------------------------------------------------------------------------
                */

                $cantidadDescontar = 0;

                if (

                    $item['tipo_venta']

                    === 'CAJA'
                ) {

                    $cantidadDescontar =

                        $item['cantidad']

                        *

                        $producto->blisters_por_caja

                        *

                        $producto->unidades_por_blister;
                }

                elseif (

                    $item['tipo_venta']

                    === 'BLISTER'
                ) {

                    $cantidadDescontar =

                        $item['cantidad']

                        *

                        $producto->unidades_por_blister;
                }

                else {

                    $cantidadDescontar =

                        $item['cantidad'];
                }

                /*
                |--------------------------------------------------------------------------
                | DESCONTAR STOCK
                |--------------------------------------------------------------------------
                */

                $producto->stock_unidades =

                    $producto->stock_unidades

                    -

                    $cantidadDescontar;

                $producto->save();
            }

            DB::commit();

            return response()->json([

                'success' => true,

                'message' =>

                    'Venta registrada correctamente',

                'data' => $venta
            ]);
        }

        catch (\Exception $e) {

            DB::rollBack();

            return response()->json([

                'success' => false,

                'error' =>

                    $e->getMessage(),

                'line' =>

                    $e->getLine(),

                'file' =>

                    $e->getFile()
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | MOSTRAR VENTA
    |--------------------------------------------------------------------------
    */

    public function show(int $id)
    {

        $venta = Venta::with([

            'cliente',

            'usuario'
        ])
        ->findOrFail($id);

        return response()->json([

            'success' => true,

            'data' => $venta
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ELIMINAR VENTA
    |--------------------------------------------------------------------------
    */

    public function destroy(int $id)
    {

        DB::beginTransaction();

        try {

            $venta = Venta::findOrFail($id);

            $detalles = DetalleVenta::where(

                'venta_id',

                $venta->id
            )->get();

            foreach (

                $detalles

                as $detalle

            ) {

                $producto = Producto::find(

                    $detalle->producto_id
                );

                if ($producto) {

                    $producto->stock_unidades +=

                        $detalle->cantidad;

                    $producto->save();
                }
            }

            DetalleVenta::where(

                'venta_id',

                $venta->id
            )->delete();

            $venta->delete();

            DB::commit();

            return response()->json([

                'success' => true,

                'message' =>

                    'Venta eliminada correctamente'
            ]);
        }

        catch (\Exception $e) {

            DB::rollBack();

            return response()->json([

                'success' => false,

                'error' =>

                    $e->getMessage()
            ], 500);
        }
    }
}
