<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TopUpCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminTopUpController extends Controller
{

    public function create(Request $request)
    {
        $users = User::where('role', 'user')->get();

        $codes = TopUpCode::query()
            ->with('user')
            ->when($request->code, fn($q) =>
                $q->where('code', 'like', '%' . $request->code . '%'))
            ->when($request->email, fn($q) =>
                $q->whereHas('user', fn($q) =>
                    $q->where('email', 'like', '%' . $request->email . '%')))
            ->when($request->is_used !== null, fn($q) =>
                $q->where('is_used', $request->is_used))
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('pages.admin.topup.create', compact('users', 'codes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'value' => 'required|numeric|min:1|max:9999',
        ]);

        $code = strtoupper(Str::random(10));

        TopUpCode::create([
            'code' => $code,
            'value' => $request->value,
            'is_used' => false,
            'used_by' => $request->user_id,
        ]);

        return redirect()->back()->with('success', 'Top-up code generated and assigned to user!');
    }
}
