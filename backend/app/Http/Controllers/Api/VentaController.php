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

        'forma_pago' =>

            'required|in:EFECTIVO,YAPE,PLIN,TARJETA',

        'monto_pagado' =>

            'nullable|numeric|min:0',

        'productos' =>

            'required|array|min:1',

        'productos.*.producto_id' =>

            'required|exists:productos,id',

        'productos.*.cantidad' =>

            'required|numeric|min:1',

        'productos.*.tipo_venta' =>

            'required|string'
    ], [

        'cliente_id.required' =>

            'Debe seleccionar un cliente',

        'cliente_id.exists' =>

            'El cliente seleccionado no existe',

        'tipo_comprobante.required' =>

            'Debe seleccionar un tipo de comprobante',

        'forma_pago.required' =>

            'Debe seleccionar una forma de pago',

        'forma_pago.in' =>

            'La forma de pago no es válida',

        'monto_pagado.numeric' =>

            'El monto pagado debe ser un número',

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

            'Debe seleccionar el tipo de venta'
    ]);

    DB::beginTransaction();

    try {

        /*
        |--------------------------------------------------------------------------
        | TOTAL
        |--------------------------------------------------------------------------
        */

        $total = 0;

        $detallesReceta = [];

        $validacionesInvolucradas = [];

        foreach (

            $request->productos

            as $index => $item

        ) {

            $producto = Producto::findOrFail(

                $item['producto_id']
            );

            $precio = $this->precioSegunTipo(

                $producto,

                $item['tipo_venta']
            );

            $cantidadDescontar = $this->cantidadEnUnidades(

                $producto,

                $item['tipo_venta'],

                $item['cantidad']
            );

            /*
            |--------------------------------------------------------------------------
            | VALIDAR STOCK
            |--------------------------------------------------------------------------
            */

            if (

                $producto->stock_unidades

                < $cantidadDescontar
            ) {

                DB::rollBack();

                return response()->json([

                    'success' => false,

                    'message' =>

                        'Stock insuficiente para '

                        . $producto->nombre
                ], 400);
            }

            /*
            |--------------------------------------------------------------------------
            | VALIDACIÓN RECETA (PRODUCTO CONTROLADO)
            |--------------------------------------------------------------------------
            */

            if ($producto->requiere_receta) {

                $detalleReceta =

                    DetalleVenta::whereNull('venta_id')

                    ->where(

                        'producto_id',

                        $producto->id
                    )

                    ->where(

                        'tipo_venta',

                        $item['tipo_venta']
                    )

                    ->whereHas('validacionReceta', function ($q) use ($request) {

                        $q->where(

                            'cliente_id',

                            $request->cliente_id
                        )

                        ->where('estado', 'APROBADO')

                        ->where('usado', false);
                    })

                    ->first();

                if (!$detalleReceta) {

                    DB::rollBack();

                    return response()->json([

                        'success' => false,

                        'message' =>

                            'Producto requiere validación del químico farmacéutico'
                    ], 403);
                }

                if (

                    $item['cantidad']

                    > $detalleReceta->cantidad
                ) {

                    DB::rollBack();

                    return response()->json([

                        'success' => false,

                        'message' =>

                            'Cantidad excede receta aprobada'
                    ], 403);
                }

                $detallesReceta[$index] = $detalleReceta;

                $validacionesInvolucradas[$detalleReceta->validacion_receta_id] =

                    $detalleReceta->validacion_receta_id;
            }

            $total += $precio * $item['cantidad'];
        }

        /*
        |--------------------------------------------------------------------------
        | SUBTOTAL / IGV
        |--------------------------------------------------------------------------
        */

        $subtotal = $total / 1.18;

        $igv = $total - $subtotal;

        /*
        |--------------------------------------------------------------------------
        | CALCULO DE VUELTO (SOLO EFECTIVO)
        |--------------------------------------------------------------------------
        */

        $montoPagado = null;

        $vuelto = 0;

        if ($request->forma_pago === 'EFECTIVO') {

            $montoPagado =

                $request->monto_pagado ?? 0;

            if ($montoPagado < round($total, 2)) {

                DB::rollBack();

                return response()->json([

                    'success' => false,

                    'message' =>

                        'El monto pagado es menor al total de la venta'
                ], 422);
            }

            $vuelto =

                $montoPagado - round($total, 2);

        } else {

            $montoPagado = round($total, 2);

            $vuelto = 0;
        }

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

            'forma_pago' =>

                $request->forma_pago,

            'subtotal' =>

                round($subtotal, 2),

            'igv' =>

                round($igv, 2),

            'total' =>

                round($total, 2),

            'monto_pagado' =>

                round($montoPagado, 2),

            'vuelto' =>

                round($vuelto, 2)
        ]);

        /*
        |--------------------------------------------------------------------------
        | DETALLE VENTA + DESCUENTO DE STOCK
        |--------------------------------------------------------------------------
        */

        foreach (

            $request->productos

            as $index => $item

        ) {

            $producto = Producto::findOrFail(

                $item['producto_id']
            );

            $precio = $this->precioSegunTipo(

                $producto,

                $item['tipo_venta']
            );

            $subtotalDetalle =

                $precio * $item['cantidad'];

            if (isset($detallesReceta[$index])) {

                $detalle = $detallesReceta[$index];

                $detalle->venta_id = $venta->id;

                $detalle->precio_unitario = $precio;

                $detalle->subtotal = $subtotalDetalle;

                $detalle->save();

            } else {

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
            }

            $cantidadDescontar = $this->cantidadEnUnidades(

                $producto,

                $item['tipo_venta'],

                $item['cantidad']
            );

            $producto->stock_unidades =

                $producto->stock_unidades

                - $cantidadDescontar;

            $producto->save();
        }

        /*
        |--------------------------------------------------------------------------
        | CERRAR VALIDACIONES USADAS EN ESTA VENTA
        |--------------------------------------------------------------------------
        */

        foreach (

            $validacionesInvolucradas

            as $validacionId

        ) {

            $validacion =

                ValidacionReceta::find($validacionId);

            if ($validacion) {

                $validacion->estado = 'VENDIDO';

                $validacion->usado = true;

                $validacion->save();
            }
        }

        DB::commit();

        return response()->json([

            'success' => true,

            'message' =>

                'Venta registrada correctamente',

            'data' =>

                $venta->load('detalles.producto', 'cliente')
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