<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException; // Agregá este import arriba de todo
class CategoryController extends Controller
{
    // GET /api/categories
    public function index(Request $request)
    {
        // Traemos las categorías del usuario que está usando la app
        $categories = Category::where('user_id', $request->user()->id)
                        ->orderBy('name', 'asc')
                        ->get();

        return response()->json($categories);
    }

    // POST /api/categories
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
        ]);

        // Le asignamos el usuario actual
        $category = Category::create([
            'name' => $validated['name'],
            'user_id' => $request->user()->id
        ]);

        return response()->json([
            'message' => 'Categoría creada con éxito',
            'data' => $category
        ], 201);
    }
    public function destroy(Request $request, $id)
    {
        // 1. Buscamos la categoría asegurándonos de que sea del usuario
        $category = Category::where('user_id', $request->user()->id)->findOrFail($id);

        try {
            // 2. Intentamos borrar
            $category->delete();
            return response()->json(['message' => 'Categoría eliminada con éxito']);
            
        } catch (QueryException $e) {
            // 3. Si salta el error de integridad (1451 es el código de SQL para restricción de llave foránea)
            if ($e->getCode() == "23000") {
                return response()->json([
                    'message' => 'No podés borrar esta categoría porque tenés gastos asociados. Primero movelos a otra categoría o borralos.'
                ], 422); // Error de entidad no procesable
            }

            return response()->json(['message' => 'Error inesperado al borrar'], 500);
        }
    }
}