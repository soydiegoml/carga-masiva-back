<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Verificar que la solicitud sea JSON
        if (!$request->isJson()) {
            return response()->json(['error' => 'La solicitud debe ser en formato JSON.'], 400);
        }

        try {
            // Validar los datos de la solicitud
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ], [
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'El correo electrónico debe ser válido.',
                'password.required' => 'La contraseña es obligatoria.',
            ]);
            
            // Intentar autenticar al usuario
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('access_token')->accessToken;

                return response()->json([
                    'message' => 'Inicio de sesión exitoso',
                    'user' => Auth::user(),
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ], 200);
            }

            // Credenciales inválidas
            return response()->json(['error' => 'Credenciales inválidas'], 401);

        } catch (ValidationException $e) {
            // Capturar errores de validación y devolverlos en la respuesta JSON
            return response()->json([
                'message' => 'Los datos proporcionados no son válidos.',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::guard('api')->user();
        if ($user) {
            // Si el usuario está autenticado, revoca el token
            $user->token()->revoke();
            return response()->json(['message' => 'Sesión cerrada correctamente'], 200);
        } else {
            // Si no está autenticado, devuelve un error
            return response()->json(['message' => 'No autorizado'], 401);
        }
    }
}
 