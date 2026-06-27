<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'nombre' => 'required|string|max:255',

            'dni' => 'required|unique:clientes,dni',

            'telefono' => 'nullable|string',

            'direccion' => 'nullable|string',

            'email' => 'nullable|email'

        ];
    }
}