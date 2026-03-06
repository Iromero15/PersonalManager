<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validamos que nos manden texto con formato de email y una clave
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Buscamos al usuario en la base de datos
        $user = User::where('email', $request->email)->first();

        // 3. Verificamos si existe y si la clave coincide con el hash
        if (! $user || ! Hash::check($request->password, $user->password)) {
            // Error 401: Unauthorized
            return response()->json([
                'message' => 'Las credenciales son incorrectas.'
            ], 401);
        }

        // 4. Si está todo bien, le generamos el pase libre (Token)
        $token = $user->createToken('wymaq_token')->plainTextToken;

        // 5. Se lo devolvemos al frontend
        return response()->json([
            'message' => 'Bienvenido, jefe.',
            'access_token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ]);
    }
    public function logout(Request $request)
    {
        // Borramos el token que se está usando en esta petición
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada, nos vemos che.'
        ]);
    }
}