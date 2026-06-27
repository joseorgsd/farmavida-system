<?php

namespace App\Services;
use Illuminate\Support\Facades\Auth;
use App\Models\Compra;
use App\Models\Producto;
use App\Models\DetalleCompra;
use App\Models\MovimientoInventario;

use Illuminate\Support\Facades\DB;
use App\Services\AuditService;
class CompraService
{

    /*
    |--------------------------------------------------------------------------
    | REGISTRAR COMPRA
    |--------------------------------------------------------------------------
    */

    public function registrar($request)
    {

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | TOTALES
            |--------------------------------------------------------------------------
            */

            $subtotal = 0;

            foreach ($request->productos as $item) {

                $subtotal +=
                    $item['cantidad']
                    * $item['precio_compra'];
            }

            $igv = $subtotal * 0.18;

            $total = $subtotal + $igv;

            /*
            |--------------------------------------------------------------------------
            | CREAR COMPRA
            |--------------------------------------------------------------------------
            */

            $compra = Compra::create([

                'proveedor_id' =>

                    $request->proveedor_id,

                'user_id' =>

                    Auth::id(),

                'numero_compra' =>

                    'C-' . time(),

                'subtotal' =>

                    round($subtotal, 2),

                'igv' =>

                    round($igv, 2),

                'total' =>

                    round($total, 2),

                'estado' =>

                    'REGISTRADA'

            ]);

            /*
            |--------------------------------------------------------------------------
            | DETALLE
            |--------------------------------------------------------------------------
            */

            foreach ($request->productos as $item) {

                $producto = Producto::findOrFail(
                    $item['producto_id']
                );

                /*
                |--------------------------------------------------------------------------
                | DETALLE COMPRA
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

                        $item['cantidad']
                        * $item['precio_compra']

                ]);

                /*
                |--------------------------------------------------------------------------
                | STOCK
                |--------------------------------------------------------------------------
                */

                $stockAnterior = $producto->stock_unidades;

                $producto->stock_unidades +=
                    $item['cantidad'];

                /*
                |--------------------------------------------------------------------------
                | ACTUALIZAR COSTO
                |--------------------------------------------------------------------------
                */

                $producto->precio_compra =
                    $item['precio_compra'];

                $producto->save();

                /*
                |--------------------------------------------------------------------------
                | MOVIMIENTO INVENTARIO
                |--------------------------------------------------------------------------
                */

                MovimientoInventario::create([

                    'producto_id' =>

                        $producto->id,

                    'user_id' =>

                        Auth::id(),

                    'tipo_movimiento' =>

                        'ENTRADA',

                    'cantidad' =>

                        $item['cantidad'],

                    'stock_anterior' =>

                        $stockAnterior,

                    'stock_nuevo' =>

                        $producto->stock_unidades,

                    'costo_unitario' =>

                        $item['precio_compra'],

                    'costo_total' =>

                        $item['cantidad']
                        * $item['precio_compra'],

                    'descripcion' =>

                        'Compra registrada:
                        ' . $compra->numero_compra

                ]);
            }
            /*
            |--------------------------------------------------------------------------
            | AUDIT LOG 
            |--------------------------------------------------------------------------
            */  
            AuditService::log(

                'CREAR',

                'COMPRAS',

                'Compra registrada correctamente',

                [

                    'compra_id' => $compra->id,

                    'numero_compra' =>

                        $compra->numero_compra,

                    'total' =>

                        $compra->total

                ]

            );
            DB::commit();

            return [

                'success' => true,

                'compra' => $compra

            ];

        } catch (\Exception $e) {

            DB::rollBack();

            return [

                'success' => false,

                'message' => $e->getMessage()

            ];
        }
    }
}