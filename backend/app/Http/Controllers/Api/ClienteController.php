<?php

namespace App\Http\Controllers\Api;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTAR
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Cliente::all()
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'    => 'required',
            // Valida que sea numérico y tenga exactamente 8 o 11 dígitos
            'dni'       => 'required|numeric|digits_between:8,11|unique:clientes,dni',
            'direccion' => 'nullable',
            'telefono'  => 'nullable',
            'email'     => 'nullable|email'
        ], [
            'required'       => 'El campo :attribute es obligatorio.',
            'unique'         => 'El :attribute ya se encuentra registrado.',
            'email'          => 'El formato del :attribute no es válido.',
            'numeric'        => 'El :attribute debe contener solo números.',
            // Mensaje personalizado para la longitud exacta del documento
            'digits_between' => 'El :attribute debe tener exactamente 8 dígitos (DNI) o 11 dígitos (RUC).'
        ], [
            'nombre'    => 'nombre completo',
            'dni'       => 'documento (DNI/RUC)',
            'direccion' => 'dirección',
            'telefono'  => 'teléfono',
            'email'     => 'correo electrónico'
        ]);

        // Validación estricta adicional para descartar longitudes como 9 o 10 dígitos
        $longitud = strlen($request->dni);
        if ($longitud !== 8 && $longitud !== 11) {
            return response()->json([
                'message' => 'El documento (DNI/RUC) debe tener exactamente 8 o 11 dígitos.',
                'errors' => [
                    'dni' => ['El documento (DNI/RUC) no tiene una longitud válida (8 o 11 dígitos).']
                ]
            ], 422);
        }

        $cliente = Cliente::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Cliente creado',
            'data'    => $cliente
        ], 201);
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        return response()->json(
            Cliente::findOrFail($id)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $request->validate([
            'nombre'    => 'required',
            'dni'       => 'required|numeric|digits_between:8,11|unique:clientes,dni,' . $id,
            'direccion' => 'nullable',
            'telefono'  => 'nullable',
            'email'     => 'nullable|email'
        ], [
            'required'       => 'El campo :attribute es obligatorio.',
            'unique'         => 'El :attribute ya se encuentra registrado.',
            'email'          => 'El formato del :attribute no es válido.',
            'numeric'        => 'El :attribute debe contener solo números.',
            'digits_between' => 'El :attribute debe tener exactamente 8 dígitos (DNI) o 11 dígitos (RUC).'
        ], [
            'nombre'    => 'nombre completo',
            'dni'       => 'documento (DNI/RUC)',
            'direccion' => 'dirección',
            'telefono'  => 'teléfono',
            'email'     => 'correo electrónico'
        ]);

        $longitud = strlen($request->dni);
        if ($longitud !== 8 && $longitud !== 11) {
            return response()->json([
                'message' => 'El documento (DNI/RUC) debe tener exactamente 8 o 11 dígitos.',
                'errors' => [
                    'dni' => ['El documento (DNI/RUC) no tiene una longitud válida (8 o 11 dígitos).']
                ]
            ], 422);
        }

        $cliente->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Cliente actualizado',
            'data'    => $cliente
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        Cliente::destroy($id);

        return response()->json([
            'success' => true,
            'message' => 'Cliente eliminado'
        ]);
    }
}
