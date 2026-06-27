<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProveedorRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        return [

            'ruc' =>

                'required|unique:proveedores,ruc,' .
                $this->id,

            'razon_social' =>

                'required|max:255',

            'telefono' =>

                'required',

            'email' =>

                'required|email',

            'direccion' =>

                'required',

            'contacto' =>

                'required'
        ];
    }

    public function messages(): array
    {

        return [

            'ruc.required' =>
                'El RUC es obligatorio',

            'razon_social.required' =>
                'La razón social es obligatoria',

            'telefono.required' =>
                'El teléfono es obligatorio',

            'email.required' =>
                'El email es obligatorio',

            'direccion.required' =>
                'La dirección es obligatoria',

            'contacto.required' =>
                'El contacto es obligatorio'
        ];
    }
}