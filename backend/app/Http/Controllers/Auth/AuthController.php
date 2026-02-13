<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Mail\UserRegistered;
use Illuminate\Support\Facades\Mail;
use App\Rules\ValidPassword;
use App\Rules\ActiveUser;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    public function register(RegisterRequest $request) {

        $user = new User();
        
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->token = AuthController::generate_token();

        $user->save();

        Mail::to($user)
            ->send(new UserRegistered($user));

        return response()->json([
            'message' => "Register Success"
        ], 201);
    }

    public function login(LoginRequest $request) {
        $request->validate(['email' => 'exists:users,email']);
        
        $user = User::where('email', $request->email)->first();
        
        $request->validate(['password' => [new ValidPassword($user->password)]]);
        $request->validate(['password' => [new ActiveUser($user)]]);

        $token = JWTAuth::fromUser($user);

        $email = $user->email;

        return response()->json(compact('email', 'token'), 200);
    }

    public static function generate_token() {
        $rb = random_bytes(32);
        $token = base64_encode($rb);
        $secureToken = uniqid('', true).$token;
        return urlencode(str_replace("/", "", $secureToken)); # Maximizamos compatibilidad para pasar el token por URL a través del email
    }

    public function me() {
        return response()->json(auth()->user(), 200);
    }

    public function activateToken(Request $request) {
        $token = $request->input('token');

        $user = User::where('token', $token)->first();
        
        if ($user) {
            if ($user->active == 1) {
                return response()->json('ERROR: Token Already Active', 410);
            }
            
            $user->active = 1;
            $user->save();

            return response()->json('OK', 200);
        }

        return response()->json('ERROR: Token no válido', 401);
    }
}
