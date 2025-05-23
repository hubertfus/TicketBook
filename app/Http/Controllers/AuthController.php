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
        if (Auth::check())
            return redirect('/')->with('status', 'You are already logged in.');
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
            $user = Auth::user();
            if ($user->role == "admin") {
                return redirect()->intended('/admin')->with('status', 'Successfully logged in as admin.');
            } else {
                return redirect()->intended('/')->with('status', 'Successfully logged in.');
            }
        }
        return back()->withErrors(["email" => "Invalid login credentials.",])->withInput();
    }
    public function logout()
    {
        Auth::logout();
        return redirect("/login");
    }
}
