<?php

namespace App\Services;

use App\Models\Producto;
use App\Models\DetalleVenta;
use App\Services\CdrService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\XmlFacturaService;
use App\Repositories\VentaRepository;
use App\Services\FirmaDigitalService;
use App\Services\ZipService;
use App\Services\SunatService;
use App\Models\MovimientoInventario;
use App\Services\AuditService;
class VentaService
{

    protected $ventaRepository;
    protected $xmlFacturaService;
    protected $firmaDigitalService;
    protected $zipService;
    protected $sunatService;
    protected $cdrService;

    public function __construct(VentaRepository $ventaRepository, XmlFacturaService $xmlFacturaService, FirmaDigitalService $firmaDigitalService, ZipService $zipService, SunatService $sunatService, CdrService $cdrService)
    {
        $this->ventaRepository = $ventaRepository;
        $this->xmlFacturaService = $xmlFacturaService;
        $this->firmaDigitalService = $firmaDigitalService;
        $this->zipService = $zipService;
        $this->sunatService = $sunatService;
        $this->cdrService = $cdrService;
    }

    public function procesarVenta(array $data)
    {

        DB::beginTransaction();

        try {

            $subtotal = 0;

            /*
            |--------------------------------------------------------------------------
            | VALIDAR STOCK
            |--------------------------------------------------------------------------
            */

            foreach ($data['productos'] as $item) {

                $producto = Producto::findOrFail($item['producto_id']);

                if ($producto->stock_unidades < $item['cantidad']) {

                    throw new \Exception(

                        'Stock insuficiente para: ' . $producto->nombre

                    );
                }

                $subtotal +=
                    $producto->precio_venta * $item['cantidad'];
            }

            /*
            |--------------------------------------------------------------------------
            | IGV 18%
            |--------------------------------------------------------------------------
            */

            $igv = $subtotal * 0.18;

            $total = $subtotal + $igv;

            /*
            |--------------------------------------------------------------------------
            | CREAR VENTA
            |--------------------------------------------------------------------------
            */

            $venta = $this->ventaRepository->crearVenta([

                'numero_venta' => 'V-' . time(),

                'cliente_id' => $data['cliente_id'],

                'user_id' => Auth::user()->id,

                'subtotal' => $subtotal,

                'igv' => $igv,

                'total' => $total,

                'tipo_comprobante' => $data['tipo_comprobante'],

                'estado' => 'PAGADO',

            ]);

            /*
            |--------------------------------------------------------------------------
            | DETALLES
            |--------------------------------------------------------------------------
            */

            foreach ($data['productos'] as $item) {

                $producto = Producto::findOrFail($item['producto_id']);

                $detalleSubtotal =
                    $producto->precio_venta * $item['cantidad'];

                DetalleVenta::create([

                    'venta_id' => $venta->id,

                    'producto_id' => $producto->id,

                    'cantidad' => $item['cantidad'],

                    'precio' => $producto->precio_venta,

                    'subtotal' => $detalleSubtotal,

                ]);

                /*
                |--------------------------------------------------------------------------
                | DESCONTAR STOCK
                |--------------------------------------------------------------------------
                */
                $stockAnterior = $producto->stock_unidades;
                $producto->stock_unidades -= $item['cantidad'];
                $producto->save();
                /*
                |--------------------------------------------------------------------------
                | REGISTRAR MOVIMIENTO DE INVENTARIO
                |--------------------------------------------------------------------------
                */
                MovimientoInventario::create([
                    'producto_id' => $producto->id,
                    'user_id' => Auth()->id,
                    'tipo_movimiento' => 'SALIDA',
                    'cantidad' => $item['cantidad'],
                    'stock_anterior' => $stockAnterior,
                    'stock_nuevo' => $producto->stock_unidades,
                    'costo_unitario' => $producto->precio_compra,
                    'costo_total' => $producto->precio_compra * $item['cantidad'],
                    'descripcion' => 'Venta realizada:'. $venta->numero_venta
                ]);
            }
            /*
            |--------------------------------------------------------------------------
            | AUDIT LOG 
            |--------------------------------------------------------------------------
            */  
            AuditService::log(

                'CREAR',

                'VENTAS',

                'Venta registrada correctamente',

                [

                    'venta_id' => $venta->id,

                    'numero_venta' =>

                        $venta->numero_venta,

                    'total' =>

                        $venta->total

                ]

            );
            DB::commit();

            $xml = $this->xmlFacturaService->generar($venta);
            $path = storage_path('app/xml');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            file_put_contents($path . '/' . $venta->numero_venta . '.xml', $xml);
            
            $xmlpath = $path . '/' . $venta->numero_venta . '.xml';
            $xmlfirmado = $path . '/' . $venta->numero_venta . '_firmado.xml';
            $this->firmaDigitalService->firmar($xmlpath, $xmlfirmado);
            $zipPath = $path . '/' . $venta->numero_venta . '.zip';
            $this->zipService->comprimir($xmlfirmado, $zipPath);
            $sunatResponse = $this->sunatService
                ->enviar($zipPath);
            $cdr = $this->cdrService->procesar($sunatResponse);

            $venta->update([

                'sunat_estado' =>

                    $cdr['estado'],

                'sunat_descripcion' =>

                    $cdr['descripcion'],

                'sunat_codigo' =>

                    $cdr['codigo'],

                'sunat_hash' =>

                    $cdr['hash'],

                'sunat_enviado_en' => now()

            ]);
            return [

                'success' => true,

                'venta' => $venta,
                
                'sunat' => $cdr

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