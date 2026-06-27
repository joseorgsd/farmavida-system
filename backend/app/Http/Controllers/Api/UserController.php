<?php

namespace App\Http\Controllers\Api;

use App\Models\User;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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

            'data' => User::with(
                'roles'
            )->latest()->paginate(10)
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CREAR
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request
    )
    {

        $request->validate([

            'name' =>

                'required',

            'email' =>

                'required|email|unique:users',

            'password' =>

                'required|min:6',

            'role' =>

                'required'
        ]);

        /*
        |--------------------------------------------------------------------------
        | USER
        |--------------------------------------------------------------------------
        */

        $user = User::create([

            'name' =>

                $request->name,

            'email' =>

                $request->email,

            'password' =>

                Hash::make(
                    $request->password
                )
        ]);

        /*
        |--------------------------------------------------------------------------
        | ROLE
        |--------------------------------------------------------------------------
        */

        $user->assignRole(
            $request->role
        );

        return response()->json([

            'success' => true,

            'message' =>

                'Usuario creado',

            'data' => $user
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

            User::with(
                'roles'
            )->findOrFail($id)
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

        $user =
            User::findOrFail($id);

        $request->validate([

            'name' =>

                'required',

            'email' =>

                'required|email|unique:users,email,' . $id
        ]);

        $user->update([

            'name' =>

                $request->name,

            'email' =>

                $request->email
        ]);

        /*
        |--------------------------------------------------------------------------
        | PASSWORD
        |--------------------------------------------------------------------------
        */

        if (
            $request->password
        ) {

            $user->update([

                'password' =>

                    Hash::make(
                        $request->password
                    )
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | ROLE
        |--------------------------------------------------------------------------
        */

        if (
            $request->role
        ) {

            $user->syncRoles([
                $request->role
            ]);
        }

        return response()->json([

            'success' => true,

            'message' =>

                'Usuario actualizado'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {

        $user =
            User::findOrFail($id);

        $user->delete();

        return response()->json([

            'success' => true,

            'message' =>

                'Usuario eliminado'
        ]);
    
    try {

        $user->assignRole($request->role);

    } catch (\Exception $e) {

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
    }
}