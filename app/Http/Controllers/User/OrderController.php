<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load(['orderItems.ticket.event']);

        return view('pages.user.orders.show', compact('order'));
    }

    public function cancel(Request $request, Order $order)
    {
        $user = auth()->user();

        if ($order->user_id !== $user->id) {
            return back()->with('error', 'You are not authorized to cancel this order.');
        }

        if ($order->status !== 'paid') {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        $order->load('orderItems.ticket.event');

        $event = $order->orderItems->first()->ticket->event ?? null;

        if (!$event) {
            return back()->with('error', 'Event not found for this order.');
        }

        $now = now();
        $eventDate = $event->date->startOfDay();

        $daysUntilEvent = $now->diffInDays($eventDate, false);

        if ($daysUntilEvent >= 14) {
            $refundRate = 1.0;
        } elseif ($daysUntilEvent >= 7) {
            $refundRate = 0.8;
        } elseif ($daysUntilEvent >= 2) {
            $refundRate = 0.6;
        } elseif ($daysUntilEvent >= 0) {
            $refundRate = 0.5;
        } else {
            return back()->with('error', 'You can no longer cancel this order.');
        }

        $refundAmount = round($order->total_price * $refundRate, 2);

        DB::transaction(function () use ($order, $user, $refundAmount, $event) {
            $user->balance += $refundAmount;
            $user->save();

            $order->status = 'cancelled';
            $order->save();

            foreach ($order->orderItems as $orderItem) {
                $ticket = $orderItem->ticket;
                $quantity = $orderItem->quantity;

                $ticket->quantity += $quantity;
                $ticket->save();

                $event->ticketSold -= $quantity;
            }

            $event->save();
        });

        return redirect()->route('user.orders.index')->with('success', "Order cancelled. Refund: PLN {$refundAmount}.");
    }
}
