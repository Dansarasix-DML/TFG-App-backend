<?php

namespace App\Http\Controllers;

use App\Mail\UserRegistered;
use App\Models\User;
use App\Rules\ValidPassword;
use App\Rules\ActiveUser;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;


class UserController extends Controller
{
    public function index() {
        return User::all();
    }

    public function register_viewold() {
        return view('register');
    }

    public function registerold(Request $request): RedirectResponse {
        # PARA VALIDAR AQUI --> https://laravel.com/docs/11.x/validation
        $request->validate([
            'name' => 'required|max:255',
            'username' => 'required|unique:users,username|max:255',
            'email' => 'required|unique:users,email|max:255|email',
            'password' => 'required|max:255',
        ]);

        $user = new User();
        
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->token = UserController::generate_token();

        $user->save();

        Mail::to($user)
            ->send(new UserRegistered($user));

        return redirect('/');
    }

    private function generate_token() {
        $rb = random_bytes(32);
        $token = base64_encode($rb);
        $secureToken = uniqid('', true).$token;
        return urlencode($secureToken); # Maximizamos compatibilidad para pasar el token por URL a través del email
    }

    public function login_view() {
        return view('login');
    }

    public function login(Request $request): RedirectResponse {
        $request->validate(
            ['email' => 'required|max:255|email'],
            ['password' => 'required'|'max:255']
        );
        $request->validate(['email' => 'exists:users,email']);
        
        $user = User::where('email', $request->email)->first();
        
        $request->validate(['password' => [new ValidPassword($user->password)]]);
        $request->validate(['password' => [new ActiveUser($user)]]);

        Auth::login($user);

        $request->session()->regenerate();
        return redirect()->intended('dashboard');
    }

    public function logout(Request $request): RedirectResponse {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

}
