<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Concerns\InteractsWithInput;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view("auth.login");
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        $credentials = $request->only("email", "password");
        if (Auth::attempt($credentials)) {
            return redirect()->intended("/homepage")->with("status", "Zalogowano pomyślnie.");
        }
        return back()->withErrors(["email" => "Nieprawidłowe dane logowania.",])->withInput();
    }
    public function logout()
    {
        Auth::logout();
        return redirect("/login");
    }
}
