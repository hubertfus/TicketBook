<?php

namespace App\Http\Controllers;

use App\Models\TopUpCode;
use Illuminate\Http\Request;

class TopUpRedemptionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $codes = TopUpCode::where('used_by', $user->id)->get();
        return view('pages.user.topup.index', compact('codes'));
    }

    public function showForm()
    {
        return view('pages.user.topup.form');
    }

    public function redeem(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:10',
        ]);

        $code = TopUpCode::where('code', strtoupper($request->code))->first();

        if (!$code) {
            return back()->withErrors(['code' => 'Invalid code.'])->withInput();
        }

        if ($code->is_used) {
            return back()->withErrors(['code' => 'This code has already been used.'])->withInput();
        }

        $user = auth()->user();
        $user->balance += $code->value;
        $user->save();

        $code->is_used = true;
        $code->used_at = now();
        $code->used_by = $user->id;
        $code->save();

        return redirect()->route('topup.form')->with('success', 'Top-up successful! Your account has been credited.');
    }
    public function redeemDirect($code)
    {
        $topUpCode = TopUpCode::where('code', $code)
            ->where('is_used', false)
            ->first();

        if (!$topUpCode) {
            return back()->with('error', 'Code is invalid or already used.');
        }

        auth()->user()->increment('balance', $topUpCode->value);

        $topUpCode->update([
            'is_used' => true,
            'used_by' => auth()->id(),
            'used_at' => now(),
        ]);

        return back()->with('success', 'Code redeemed successfully!');
    }
}
