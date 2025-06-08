<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Refund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RefundController extends Controller
{
    public function index()
    {
        $refunds = Refund::with('order.user')->latest()->get();
        return view('pages.admin.refunds.index', compact('refunds'));
    }

    public function approve(Refund $refund)
    {
        if ($refund->status !== 'requested') {
            return back()->with('error', 'Refund already processed.');
        }

        DB::transaction(function () use ($refund) {
            $user = $refund->order->user;

            // Add refund amount to balance
            $user->balance += $refund->order->total_price;
            $user->save();

            // Update order and refund status
            $refund->order->update(['status' => 'refunded']);
            $refund->update(['status' => 'approved']);
        });

        return back()->with('success', 'Refund approved and processed.');
    }

    public function reject(Refund $refund)
    {
        if ($refund->status !== 'requested') {
            return back()->with('error', 'Refund already processed.');
        }

        $refund->update(['status' => 'rejected']);
        return back()->with('info', 'Refund rejected.');
    }
}
