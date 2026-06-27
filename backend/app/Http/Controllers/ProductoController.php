<?php

namespace App\Http\Controllers;

use App\Models\Producto;

use Illuminate\Http\Request;

class ProductoController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | LISTAR
    |--------------------------------------------------------------------------
    */

    public function index()
    {

        $productos = Producto::latest()->get();

        return response()->json([

            'success' => true,

            'data' => $productos
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | GUARDAR
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {

        $request->validate([

            'nombre' =>

                'required|string|max:255',

            'stock_unidades' =>

                'required|numeric|min:0',

            'precio_compra' =>

                'required|numeric|min:0',

            'precio_venta' =>

                'required|numeric|min:0',

            'precio_blister' =>

                'required|numeric|min:0',

            'precio_unidad' =>

                'required|numeric|min:0',

            'blisters_por_caja' =>

                'required|numeric|min:1',

            'unidades_por_blister' =>

                'required|numeric|min:1',
        ]);

        $producto = Producto::create([

            'nombre' =>

                $request->nombre,

            

            'stock_unidades' =>

                $request->stock_unidades,

            'precio_compra' =>

                $request->precio_compra,

            'precio_venta' =>

                $request->precio_venta,

            'precio_blister' =>

                $request->precio_blister,

            'precio_unidad' =>

                $request->precio_unidad,

            'blisters_por_caja' =>

                $request->blisters_por_caja,

            'unidades_por_blister' =>

                $request->unidades_por_blister,

            'requiere_receta' =>

                $request->requiere_receta ?? false
        ]);

        return response()->json([

            'success' => true,

            'message' =>

                'Producto registrado correctamente',

            'data' => $producto
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | MOSTRAR
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {

        $producto = Producto::findOrFail($id);

        return response()->json([

            'success' => true,

            'data' => $producto
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {

        $producto = Producto::findOrFail($id);

        $producto->update($request->all());

        return response()->json([

            'success' => true,

            'message' =>

                'Producto actualizado correctamente',

            'data' => $producto
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ELIMINAR
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {

        $producto = Producto::findOrFail($id);

        $producto->delete();

        return response()->json([

            'success' => true,

            'message' =>

                'Producto eliminado correctamente'
        ]);
    }
}
