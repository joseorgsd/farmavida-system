<?php

namespace App\Http\Controllers\Api;

use App\Models\Proveedor;

use App\Http\Controllers\Controller;

use App\Http\Requests\ProveedorRequest;

class ProveedorController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | LISTAR
    |--------------------------------------------------------------------------
    */

    public function index()
    {

        return Proveedor::latest()->get();
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        ProveedorRequest $request
    )
    {

        $proveedor = Proveedor::create(

            $request->all()
        );

        return response()->json([

            'message' =>
                'Proveedor registrado',

            'proveedor' =>
                $proveedor

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        Proveedor $proveedore
    )
    {

        return $proveedore;
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(

        ProveedorRequest $request,

        Proveedor $proveedore

    )
    {

        $proveedore->update(

            $request->all()
        );

        return response()->json([

            'message' =>
                'Proveedor actualizado'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Proveedor $proveedore
    )
    {

        $proveedore->delete();

        return response()->json([

            'message' =>
                'Proveedor eliminado'
        ]);
    }
}