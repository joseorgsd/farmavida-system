<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompraRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        return [

            'proveedor_id' =>

                'required|exists:proveedores,id',

            'productos' =>

                'required|array|min:1',

            'productos.*.producto_id' =>

                'required|exists:productos,id',

            'productos.*.cantidad' =>

                'required|integer|min:1',

            'productos.*.precio_compra' =>

                'required|numeric|min:0'
        ];
    }
}