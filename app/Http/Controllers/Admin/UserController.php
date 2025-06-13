<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user()?->isAdmin()) {
            abort(403);
        }

        $query = User::query();

        if ($request->filled('name')) {
            $name = strtolower($request->input('name'));
            $query->whereRaw('LOWER(name) LIKE ?', ["%{$name}%"]);
        }

        if ($request->filled('email')) {
            $email = strtolower($request->input('email'));
            $query->whereRaw('LOWER(email) LIKE ?', ["%{$email}%"]);
        }

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        $users = $query->latest()->paginate(12);
        $roles = User::select('role')->distinct()->pluck('role');

        return view('pages.admin.users.index', compact('users', 'roles'));
    }

    public function show(User $user)
    {
        if (!Auth::user()?->isAdmin()) {
            abort(403);
        }

        return view('pages.admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        if (!Auth::user()?->isAdmin()) {
            abort(403);
        }

        return view('pages.admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if (!Auth::user()?->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|min:5',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,user'
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User updated!');
    }
    public function destroy(User $user)
    {
        if (!Auth::user()?->isAdmin()) {
            abort(403);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted!');
    }
}
