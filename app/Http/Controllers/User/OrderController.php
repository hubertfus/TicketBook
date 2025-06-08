<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Order::with('orderItems.ticket')
            ->where('user_id', $user->id);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->input('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->input('to_date'));
        }

        $orders = $query->latest()->paginate(10);
        $statuses = Order::select('status')->distinct()->pluck('status');

        return view('pages.user.orders.index', compact('orders', 'statuses'));
    }

    public function show(Order $order)
    {
        // Sprawdzenie autoryzacji - czy zamówienie należy do zalogowanego użytkownika
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Załadowanie relacji z eventami
        $order->load(['orderItems.ticket.event']);

        return view('pages.user.orders.show', compact('order'));
    }
}
