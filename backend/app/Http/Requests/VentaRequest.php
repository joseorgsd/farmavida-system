<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VentaRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        return [

            'cliente_id' =>

                'required|exists:clientes,id',

            'tipo_comprobante' =>

                'required|in:BOLETA,FACTURA',

            'productos' =>

                'required|array|min:1',

            'productos.*.producto_id' =>

                'required|exists:productos,id',

            'productos.*.presentacion_id' =>

                'required|exists:producto_presentaciones,id',

            'productos.*.cantidad' =>

                'required|integer|min:1'
        ];
    }
}