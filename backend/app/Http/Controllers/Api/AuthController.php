<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
    {

        $credentials = $request->validate([

            'email' => 'required|email',

            'password' => 'required'

        ]);

        /*
        |--------------------------------------------------------------------------
        | VALIDAR
        |--------------------------------------------------------------------------
        */

        if (!Auth::attempt($credentials)) {

            return response()->json([

                'message' =>

                    'Credenciales incorrectas'

            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | USUARIO
        |--------------------------------------------------------------------------
        */

        /** @var \App\Models\User $user */
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | TOKEN
        |--------------------------------------------------------------------------
        */

        $token = $user->createToken(
            'api-token'
        )->plainTextToken;

        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([ 
            'token' => $token, 
            'user' => [ 
                'id' => $user->id, 
                'name' => $user->name, 
                'email' => $user->email, 
                'rol' => $user->rol 
            ] 
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {

        $request->user()

            ->currentAccessToken()

            ->delete();

        return response()->json([

            'message' => 'Logout exitoso'

        ]);
    }
}