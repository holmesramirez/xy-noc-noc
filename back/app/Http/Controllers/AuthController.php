<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Back\User; // Cambio en la importación del modelo User

class AuthController extends Controller
{
    public function login(Request $request) {
        $credentials = $request->only('email', 'password');
    
        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            $token = $user->createToken('AppName')->accessToken;
            return response()->json(['token' => $token], 200);
        }
    
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function forgotPassword(Request $request) {
        // Lógica para enviar correo de recuperación de contraseña
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        // Aquí se enviaría el correo de recuperación de contraseña al usuario
        return response()->json(['message' => 'Correo de recuperación enviado'], 200);
    }

    public function register(Request $request) {
        // Lógica para registrar un nuevo usuario por un super admin
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Aquí se enviaría el correo de bienvenida al nuevo usuario
        return response()->json(['message' => 'Usuario registrado con éxito'], 201);
    }
}
