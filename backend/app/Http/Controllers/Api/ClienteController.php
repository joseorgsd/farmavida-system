<?php

namespace App\Http\Controllers\Api;

use App\Models\Cliente;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

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

    public function store(
        Request $request
    )
    {

        $request->validate([

            'nombre' =>

                'required',

        
            'dni' =>

                'required|unique:clientes,dni',

            'direccion' =>

                'nullable',

            'telefono' =>

                'nullable',

            'email' =>

                'nullable|email'
        ]);

        $cliente = Cliente::create([

            'nombre' =>

                $request->nombre,


            'dni' =>

                $request->dni,

            'direccion' =>

                $request->direccion,

            'telefono' =>

                $request->telefono,

            'email' =>

                $request->email
        ]);

        return response()->json([

            'success' => true,

            'message' =>

                'Cliente creado',

            'data' => $cliente
        ]);
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

    public function update(
        Request $request,
        $id
    )
    {

        $cliente =
            Cliente::findOrFail($id);

        $request->validate([

            'nombre' =>

                'required',

            

            'dni' =>

                'required|unique:clientes,dni,' . $id,

            'email' =>

                'nullable|email'
        ]);

        $cliente->update([

            'nombre' =>

                $request->nombre,

            

            'dni' =>

                $request->dni,

            'direccion' =>

                $request->direccion,

            'telefono' =>

                $request->telefono,

            'email' =>

                $request->email
        ]);

        return response()->json([

            'success' => true,

            'message' =>

                'Cliente actualizado'
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

            'message' =>

                'Cliente eliminado'
        ]);
    }
}