<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // En update, $this->route('producto') es el id del producto actual,
        // para que la validación 'unique' no se rechace a sí misma al editar.
        $productoId = $this->route('producto');

        return [
            
            'nombre' => ['required', 'string', 'max:255', Rule::unique('productos', 'nombre')->ignore($productoId)],
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock_unidades' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            
            // Campos adicionales del formulario completo
            'unidades_por_blister' => 'nullable|integer|min:1',
            'blisters_por_caja' => 'nullable|integer|min:1',
            'requiere_receta' => 'nullable|boolean',
            'producto_controlado' => 'nullable|boolean',
            'precio_blister' => 'nullable|numeric|min:0',
            'precio_unidad' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'codigo.required' => 'El código es obligatorio',
            'nombre.required' => 'El nombre es obligatorio',

            'precio_compra.required' => 'El precio de compra es obligatorio',
            'precio_venta.required' => 'El precio de venta es obligatorio',
            'stock_unidades.required' => 'El stock es obligatorio',
            'stock_minimo.required' => 'El stock mínimo es obligatorio',
            'lote.required' => 'El lote es obligatorio',
            'fecha_vencimiento.required' => 'La fecha de vencimiento es obligatoria',
            'codigo_barras.required' => 'El código de barras es obligatorio',
            'codigo.unique' => 'El código ya existe',
            'nombre.unique' => 'El nombre ya existe',
            'lote.unique' => 'El lote ya existe',
            'codigo_barras.unique' => 'El código de barras ya existe',
        ];
    }
}