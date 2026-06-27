<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\Cliente;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AnalyticsController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD GENERAL
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {

        $data = Cache::remember(

            'dashboard_kpis',

            60,

            function () {

                return [

                    /*
                    |--------------------------------------------------------------------------
                    | KPIs
                    |--------------------------------------------------------------------------
                    */

                    'ventas_total' =>

                        Venta::count(),

                    'clientes_total' =>

                        Cliente::count(),

                    'productos_total' =>

                        Producto::count(),

                    'ingresos_total' =>

                        Venta::sum('total'),

                    /*
                    |--------------------------------------------------------------------------
                    | VENTAS HOY
                    |--------------------------------------------------------------------------
                    */

                    'ventas_hoy' =>

                        Venta::whereDate(

                            'created_at',

                            now()

                        )->count(),

                    /*
                    |--------------------------------------------------------------------------
                    | INGRESOS HOY
                    |--------------------------------------------------------------------------
                    */

                    'ingresos_hoy' =>

                        Venta::whereDate(

                            'created_at',

                            now()

                        )->sum('total')

                ];
            }

        );

        return response()->json($data);
    }

    /*
    |--------------------------------------------------------------------------
    | VENTAS DIARIAS
    |--------------------------------------------------------------------------
    */

    public function ventasDiarias()
    {

        $ventas = Venta::select(

            DB::raw('DATE(created_at) as fecha'),

            DB::raw('SUM(total) as total')

        )

        ->groupBy('fecha')

        ->orderBy('fecha')

        ->get();

        return response()->json($ventas);
    }

    /*
    |--------------------------------------------------------------------------
    | TOP PRODUCTOS
    |--------------------------------------------------------------------------
    */

    public function topProductos()
    {

        $productos = DB::table('detalle_ventas')

            ->join(

                'productos',

                'detalle_ventas.producto_id',

                '=',

                'productos.id'

            )

            ->select(

                'productos.nombre',

                DB::raw(

                    'SUM(detalle_ventas.cantidad)
                    as total_vendido'

                )

            )

            ->groupBy('productos.nombre')

            ->orderByDesc('total_vendido')

            ->limit(10)

            ->get();

        return response()->json($productos);
    }

    /*
    |--------------------------------------------------------------------------
    | STOCK CRÍTICO
    |--------------------------------------------------------------------------
    */

    public function stockCritico()
    {

        $productos = Producto::whereColumn(

            'stock_unidades',

            '<=',

            'stock_minimo'

        )->get();

        return response()->json($productos);
    }

    /*
    |--------------------------------------------------------------------------
    | INVENTARIO VALORADO
    |--------------------------------------------------------------------------
    */
    public function inventarioValorizado()
    {

        $productos = \App\Models\Producto::all();

        $total = 0;

        foreach ($productos as $producto) {

            $total +=

                $producto->stock_unidades
                * $producto->precio_compra;
        }

        return response()->json([

            'inventario_valorizado' =>

                round($total, 2)

        ]);
    }
}