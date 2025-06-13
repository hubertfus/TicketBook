<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Refund;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Order::with(['orderItems.ticket', 'refund'])
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
        try {
            $user = auth()->user();

            if ($order->user_id !== $user->id) {
                throw new \Exception('You are not authorized to cancel this order');
            }

            if (!in_array($order->status, ['paid', 'pending'])) {
                throw new \Exception('Cannot cancel order with status: '.$order->status);
            }

            $order->load(['orderItems.ticket.event', 'user']);
            $event = $order->orderItems->first()->ticket->event ?? null;

            if (!$event) {
                throw new \Exception('Associated event not found');
            }

            $eventDate = Carbon::parse($event->date)->setTimezone(config('app.timezone'))->startOfDay();
            $currentDate = Carbon::now()->setTimezone(config('app.timezone'))->startOfDay();
            $daysUntilEvent = $currentDate->diffInDays($eventDate, false);


            Log::debug('Cancellation details', [
                'order_id' => $order->id,
                'event_date' => $eventDate,
                'current_date' => $currentDate,
                'days_until_event' => $daysUntilEvent,
                'timezone' => config('app.timezone')
            ]);

            if ($daysUntilEvent < 0) {
                throw new \Exception('Event has already ended');
            }

            $refundRate = $this->calculateRefundRate($daysUntilEvent);
            $refundAmount = round($order->total_price * $refundRate, 2);

            DB::transaction(function () use ($order, $user, $refundAmount, $event) {

                $order->update(['status' => 'cancelled']);

                $user->increment('balance', $refundAmount);

                foreach ($order->orderItems as $item) {
                    if ($event->ticketSold >= $item->quantity) {
                        $event->decrement('ticketSold', $item->quantity);
                    }
                }
            });

            return redirect()->route('user.orders.index')
                   ->with('success', "Order cancelled. Refunded: ".number_format($refundAmount, 2)." PLN (".($refundRate*100)."%)")
                   ->with('debug_info', "Days until event: $daysUntilEvent");

        } catch (\Exception $e) {
            Log::error('Order cancellation failed: '.$e->getMessage());
            return back()->with('error', 'Cancellation failed: '.$e->getMessage());
        }
    }

    private function calculateRefundRate($daysUntilEvent)
    {

        $daysUntilEvent = max(0, $daysUntilEvent);

        return match(true) {
            $daysUntilEvent >= 15 => 1.0,
            $daysUntilEvent >= 8 => 0.8,
            $daysUntilEvent >= 3 => 0.6,
            $daysUntilEvent >= 1 => 0.5,
            $daysUntilEvent == 0 => 0.3,
            default => 0.0
        };
    }

    public function refund(Request $request, Order $order)
    {
        $user = auth()->user();

        if ($order->user_id !== $user->id) {
            return back()->with('error', 'You are not authorized to refund this order.');
        }

        if ($order->status !== 'cancelled') {
            return back()->with('error', 'Only cancelled orders can be refunded.');
        }

        $order->load('orderItems.ticket.event');
        $event = $order->orderItems->first()->ticket->event ?? null;

        if (!$event || $event->date >= now()) {
            return back()->with('error', 'Refund is only available for past events.');
        }

        try {
            DB::transaction(function () use ($order, $user) {
                $user->balance += $order->total_price;
                $user->save();

                $order->status = 'refunded';
                $order->save();
            });

            return redirect()->route('user.orders.index')->with('success',
                "Order refunded successfully. Amount: PLN " . number_format($order->total_price, 2, ',', ' '));
        } catch (\Exception $e) {
            Log::error('Order refund failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to process refund. Please try again.');
        }
    }

    public function submitRefundRequest(Request $request, Order $order)
    {
        $user = auth()->user();

        if ($order->user_id !== $user->id) {
            return back()->with('error', 'You are not authorized.');
        }

        $event = optional($order->orderItems->first()->ticket->event);

        if (!$event || $event->date->isFuture()) {
            return back()->with('error', 'You can request a refund only after the event has ended.');
        }

        if ($order->refund) {
            return back()->with('error', 'Refund request already submitted.');
        }

        $request->validate([
            'reason' => 'required|string|min:10',
        ]);

        Refund::create([
            'order_id' => $order->id,
            'user_id' => $user->id,
            'reason' => $request->reason,
            'status' => 'requested',
        ]);

        return back()->with('success', 'Your refund request has been sent.');
    }

    public function downloadConfirmation(Order $order)
    {
        if (auth()->id() !== $order->user_id) {
            abort(403);
        }

        $pdf = Pdf::loadView('pdf.order-confirmation', ['order' => $order]);
        return $pdf->download('order-confirmation-' . $order->id . '.pdf');
    }
}
