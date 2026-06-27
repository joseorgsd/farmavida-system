<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\Cliente;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = Carbon::today();

        // Ventas de HOY, excluyendo ANULADO
        $ventasHoy = Venta::where('estado', 'PAGADO')
            ->whereDate('created_at', $hoy)
            ->sum('total');
        $numeroVentasHoy = Venta::where('estado', 'PAGADO')
            ->whereDate('created_at', $hoy)
            ->count();
        // Productos activos (Producto usa SoftDeletes, ya excluye eliminados automáticamente)
        $productos = Producto::count();

        // Clientes activos (Cliente NO usa SoftDeletes, filtramos manualmente)
        $clientes = Cliente::whereNull('deleted_at')->count();

        // Stock crítico: productos cuyo stock actual está en el mínimo o por debajo
        $stockCritico = Producto::whereColumn('stock_unidades', '<=', 'stock_minimo')
            ->count();

        // Ventas reales de los últimos 7 días, agrupadas por día
        $inicio = Carbon::today()->subDays(6)->startOfDay();

        $ventasSemana = Venta::where('estado', 'PAGADO')
            ->where('created_at', '>=', $inicio)
            ->selectRaw('DATE(created_at) as fecha, SUM(total) as total')
            ->groupBy('fecha')
            ->get()
            ->keyBy('fecha');

        $diasSemana = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];

        $categorias = [];
        $valores = [];

        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::today()->subDays($i);
            $clave = $fecha->toDateString();

            $categorias[] = $diasSemana[$fecha->dayOfWeek];
            $valores[] = isset($ventasSemana[$clave])
                ? (float) $ventasSemana[$clave]->total
                : 0;
        }

        return response()->json([
            'ventas' => (float) $ventasHoy,
            'numero_ventas'=> $numeroVentasHoy,
            'productos' => $productos,
            'clientes' => $clientes,
            'stock_critico' => $stockCritico,
            'grafico' => [
                'categorias' => $categorias,
                'valores' => $valores,
            ],
        ]);
    }
}