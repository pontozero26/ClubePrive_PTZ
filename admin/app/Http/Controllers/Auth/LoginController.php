<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Providers\RouteServiceProvider;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        dd($request->all());
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [            
            'email.required' => 'O campo email é obrigatório.',
            'password.required' => 'O campo Senha é obrigatório.',    
        ]);

        $credentials = [
            'email' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            Log::channel('db')->info('Login', ['user' => $user->name, 'ip' => $request->ip()]);
            if($request->header('Accept') == 'application/json') {
                $token = $request->user()->createToken('auth_token')->plainTextToken;
                return response()->json(['access_token' => $token, 'token_type' => 'Bearer']);
            }
            else {
                return redirect()->intended(RouteServiceProvider::HOME);
            }
        }

        return back()->withErrors([
            'email' => __('auth.failed'),
        ]);
    }
}
