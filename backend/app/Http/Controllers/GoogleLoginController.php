<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;
use Google\Client as GoogleClient;
use App\Http\Controllers\Auth\AuthController;

class GoogleLoginController extends Controller
{
    public function handleGoogleCallback(Request $request)
    {
        $token = $request->input('token');

        if (!$token) {
            return response()->json(['error' => 'Token no proporcionado'], 400);
        }

        try {
            $googleUser = $this->decodeJwt($token);

            // Verificar si el email está presente
            if (!$googleUser["email"]) {
                return response()->json(['error' => 'No se pudo obtener el email del usuario de Google'], 400);
            }

            // Buscar el usuario en la base de datos por email
            $user = User::where('email', $googleUser["email"])->first();

            // Si no existe el usuario, crearlo          
            if (!$user) {
                $username = strtolower(strstr($googleUser["email"], '@', true) . rand(100000, 999999));
                $user = User::create([
                    'name' => $googleUser["name"],
                    'email' => $googleUser["email"],
                    'password' => bcrypt(Str::random(16)), // No real password needed
                    'username' => $username,
                    'profile_img' => $username."/profile_img.png",
                    'token' => AuthController::generate_token()
                ]);
            } else {
                $username = $user->username;
            }

            // Generar un token JWT para el usuario
            $jwtToken = JWTAuth::fromUser($user);

            // Retornar el token JWT en la respuesta
            return response()->json(['email' => $user->email, 'token' => $jwtToken, 'profile_img' => $googleUser["picture"], 'username' => $username], 200);
        } catch (\Exception $e) {
            // Manejar excepciones y retornar un mensaje de error
            return response()->json(['error' => 'Error al autenticar con Google: ' . $e->getMessage()], 500);
        }
    }

    private function decodeJwt($idToken)
    {
        $client = new GoogleClient(['client_id' => env('GOOGLE_CLIENT_ID')]);
        $payload = $client->verifyIdToken($idToken);

        if ($payload) {
            return $payload;
        } else {
            throw new \Exception('Invalid ID token');
        }
    }
}
