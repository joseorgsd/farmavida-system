<?php

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\VentaController;
use App\Http\Controllers\Api\CompraController;
use App\Http\Controllers\Api\ProveedorController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\ReporteController;
use App\Http\Controllers\Api\KardexController;
use App\Http\Controllers\Api\AuditController;
use App\Http\Controllers\Api\PdfController;
use App\Http\Controllers\Api\SolicitudRecetaController;
use App\Http\Controllers\Api\ValidacionRecetaController;

use App\Http\Controllers\ProductoController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::post(

    '/login',

    [AuthController::class, 'login']
);

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PERFIL
    |--------------------------------------------------------------------------
    */

    Route::get(

        '/profile',

        function (Request $request) {

            return $request->user();
        }
    );

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get(

        '/dashboard',

        [DashboardController::class, 'index']
    );

    /*
    |--------------------------------------------------------------------------
    | PRODUCTOS
    |--------------------------------------------------------------------------
    */

    Route::apiResource(

        'productos',

        ProductoController::class
    );

    /*
    |--------------------------------------------------------------------------
    | CLIENTES
    |--------------------------------------------------------------------------
    */

    Route::apiResource(

        'clientes',

        ClienteController::class
    );

    /*
    |--------------------------------------------------------------------------
    | PROVEEDORES
    |--------------------------------------------------------------------------
    */

    Route::apiResource(

        'proveedores',

        ProveedorController::class
    );

    /*
    |--------------------------------------------------------------------------
    | USUARIOS
    |--------------------------------------------------------------------------
    */

    Route::apiResource(

        'usuarios',

        UserController::class
    );

    /*
    |--------------------------------------------------------------------------
    | VENTAS
    |--------------------------------------------------------------------------
    */

    Route::apiResource(

        'ventas',

        VentaController::class
    );

    /*
    |--------------------------------------------------------------------------
    | COMPRAS
    |--------------------------------------------------------------------------
    */

    Route::apiResource(

        'compras',

        CompraController::class
    );

    /*
    |--------------------------------------------------------------------------
    | VALIDACIONES RECETA
    |--------------------------------------------------------------------------
    */

    Route::get(

        '/validaciones-receta',

        [

            ValidacionRecetaController::class,

            'index'
        ]
    );

    Route::post(

        '/validaciones-receta',

        [

            ValidacionRecetaController::class,

            'store'
        ]
    );

    Route::put(

        '/validaciones-receta/{id}/aprobar',

        [

            ValidacionRecetaController::class,

            'aprobar'
        ]
    );

    Route::put(

        '/validaciones-receta/{id}/rechazar',

        [

            ValidacionRecetaController::class,

            'rechazar'
        ]
    );
    Route::put(
        '/validaciones-receta/{id}',
        [ValidacionRecetaController::class, 'actualizarDatos']
    );

    Route::post(
        '/validaciones-receta/{id}/productos',
        [ValidacionRecetaController::class, 'agregarProducto']
    );

    Route::delete(
        '/validaciones-receta/{id}/productos/{detalleId}',
        [ValidacionRecetaController::class, 'quitarProducto']
    );
    Route::get(
        '/validaciones-receta/pendientes-venta',
        [ValidacionRecetaController::class, 'pendientesVenta']
    );
    /*
    |--------------------------------------------------------------------------
    | SOLICITUDES RECETA
    |--------------------------------------------------------------------------
    */

    Route::get(

        '/solicitudes-receta',

        [

            SolicitudRecetaController::class,

            'index'
        ]
    );

    Route::post(

        '/solicitudes-receta',

        [

            SolicitudRecetaController::class,

            'store'
        ]
    );

    Route::post(

        '/solicitudes-receta/{id}/aprobar',

        [

            SolicitudRecetaController::class,

            'aprobar'
        ]
    );

    Route::post(

        '/solicitudes-receta/{id}/rechazar',

        [

            SolicitudRecetaController::class,

            'rechazar'
        ]
    );

    /*
    |--------------------------------------------------------------------------
    | PDF
    |--------------------------------------------------------------------------
    */

    Route::get(

        '/ventas/{id}/ticket',

        [

            PdfController::class,

            'ticket'
        ]
    );

    /*
    |--------------------------------------------------------------------------
    | ANALYTICS
    |--------------------------------------------------------------------------
    */

    Route::prefix('analytics')->group(function () {

        Route::get(

            '/dashboard',

            [

                AnalyticsController::class,

                'dashboard'
            ]
        );

        Route::get(

            '/ventas-diarias',

            [

                AnalyticsController::class,

                'ventasDiarias'
            ]
        );

        Route::get(

            '/top-productos',

            [

                AnalyticsController::class,

                'topProductos'
            ]
        );

        Route::get(

            '/stock-critico',

            [

                AnalyticsController::class,

                'stockCritico'
            ]
        );

        Route::get(

            '/inventario-valorizado',

            [

                AnalyticsController::class,

                'inventarioValorizado'
            ]
        );
    });

    /*
    |--------------------------------------------------------------------------
    | REPORTES
    |--------------------------------------------------------------------------
    */

    Route::prefix('reportes')->group(function () {

        Route::get(

            '/ventas-excel',

            [

                ReporteController::class,

                'ventasExcel'
            ]
        );

        Route::get(

            '/ventas-pdf',

            [

                ReporteController::class,

                'ventasPdf'
            ]
        );
    });

    /*
    |--------------------------------------------------------------------------
    | KARDEX
    |--------------------------------------------------------------------------
    */

    Route::get(

        '/kardex',

        [

            KardexController::class,

            'index'
        ]
    );

    /*
    |--------------------------------------------------------------------------
    | AUDITORÍA
    |--------------------------------------------------------------------------
    */

    Route::middleware([

        'role:ADMIN|AUDITOR'

    ])->group(function () {

        Route::get(

            '/audit-logs',

            [

                AuditController::class,

                'index'
            ]
        );
    });

    /*
    |--------------------------------------------------------------------------
    | PERMISOS ANALYTICS
    |--------------------------------------------------------------------------
    */

    Route::middleware([

        'permission:ver analytics'

    ])->group(function () {

        Route::get(

            '/analytics/dashboard',

            [

                AnalyticsController::class,

                'dashboard'
            ]
        );
    });
});
