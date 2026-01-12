<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;


class AuthTokenController extends Controller
{
    public function login(Request $request)
    {
       // 1 Validar los datos que llegan
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'device_name' => ['nullable', 'string'],
        ]);


       // 2 Buscar el usuario por email
        $user = User::where('email', $request->email)->first();


       // 3 Comprobar que el usuario existe y la contraseña es correcta
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Credenciales incorrectas.'],
            ]);
        }


       // 4 Nombre del dispositivo (Postman, navegador, etc.)
        $deviceName = $request->device_name ?? 'api-client';


       // 5 Crear token y devolverlo
        return response()->json([
            'token' => $user->createToken($deviceName)->plainTextToken,
            'user'  => $user,
        ]);
    }
    public function logout(Request $request)
    {
    $request->user()->currentAccessToken()->delete();

    return response()->json(['message' => 'Logged out']);
    }
}
