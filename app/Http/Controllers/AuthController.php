<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegistrationForm()
    {
        return view('pages.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|min:5',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        return redirect('/login');
    }

    public function showLoginForm()
    {
        $user = Auth::user();
        if (Auth::check() && $user->role === 'admin') {
            return redirect("/admin");
        }
        if (Auth::check() && $user->role === 'user') {
            return redirect("/");
        }
        return view("pages.auth.login");
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->intended('/admin')->with('status', 'Logged in as admin.');
            }

            if ($user->role === 'user') {
                return redirect()->intended('/')->with('status', 'Logged in as user.');
            }
        }

        return back()->withErrors([
            'email' => 'Incorrect credentials.',
        ])->withInput();
    }

    public function logout()
    {
        Auth::logout();
        return redirect("/login");
    }
}
