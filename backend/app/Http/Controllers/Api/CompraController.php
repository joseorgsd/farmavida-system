<?php

namespace App\Http\Controllers\Api;

use App\Models\Compra;

use App\Models\Producto;

use Illuminate\Support\Facades\DB;

use App\Models\DetalleCompra;

use App\Http\Controllers\Controller;

use App\Http\Requests\CompraRequest;
use Illuminate\Support\Facades\Auth;
class CompraController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | LISTAR
    |--------------------------------------------------------------------------
    */

    public function index()
    {

        return Compra::with(

            'proveedor',

            'detalles.producto'

        )->latest()->get();
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTRAR
    |--------------------------------------------------------------------------
    */

    public function store(
        CompraRequest $request
    )
    {

        DB::beginTransaction();

        try {

            $subtotal = 0;

            /*
            |--------------------------------------------------------------------------
            | TOTAL
            |--------------------------------------------------------------------------
            */

            foreach (
                $request->productos
                as $item
            ) {

                $subtotal +=
                    $item['cantidad']
                    *
                    $item['precio_compra'];
            }

            /*
            |--------------------------------------------------------------------------
            | IGV
            |--------------------------------------------------------------------------
            */

            $igv = $subtotal * 0.18;

            $total =
                $subtotal + $igv;

            /*
            |--------------------------------------------------------------------------
            | USER
            |--------------------------------------------------------------------------
            */
            /**
             * @var \App\Models\User $user
             */
            $user = Auth::user();

            /*
            |--------------------------------------------------------------------------
            | COMPRA
            |--------------------------------------------------------------------------
            */

            $compra = Compra::create([

                'numero_compra' =>

                    'C-' . time(),

                'proveedor_id' =>

                    $request->proveedor_id,

                'user_id' =>

                    $user->id,

                'subtotal' =>

                    $subtotal,

                'igv' =>

                    $igv,

                'total' =>

                    $total
            ]);

            /*
            |--------------------------------------------------------------------------
            | DETALLES
            |--------------------------------------------------------------------------
            */

            foreach (
                $request->productos
                as $item
            ) {

                $producto = Producto::findOrFail(
                    $item['producto_id']
                );

                $sub =
                    $item['cantidad']
                    *
                    $item['precio_compra'];

                /*
                |--------------------------------------------------------------------------
                | DETALLE
                |--------------------------------------------------------------------------
                */

                DetalleCompra::create([

                    'compra_id' =>

                        $compra->id,

                    'producto_id' =>

                        $producto->id,

                    'cantidad' =>

                        $item['cantidad'],

                    'precio_compra' =>

                        $item['precio_compra'],

                    'subtotal' =>

                        $sub
                ]);

                /*
                |--------------------------------------------------------------------------
                | STOCK
                |--------------------------------------------------------------------------
                */

                $producto->stock_unidades +=
                    $item['cantidad'];

                $producto->save();
            }

            DB::commit();

            return response()->json([

                'success' => true,

                'message' =>
                    'Compra registrada',

                'compra' =>
                    $compra
            ]);
        }

        catch (\Exception $e) {

            DB::rollBack();

            return response()->json([

                'success' => false,

                'message' =>
                    $e->getMessage()

            ], 500);
        }
    }
}