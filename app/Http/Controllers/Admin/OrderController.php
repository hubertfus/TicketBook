<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems', 'refund']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('user')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($request->input('user')) . '%']);
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->input('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->input('to_date'));
        }

        $orders = $query->latest()->paginate(10);
        $statuses = Order::select('status')->distinct()->pluck('status');

        return view('pages.admin.orders.index', compact('orders', 'statuses'));
    }

    public function create()
    {
        $query = Ticket::with('event');

        if (request('search')) {
            $search = strtolower(request('search'));
            $query->whereHas('event', fn($q) => $q->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"]))
                ->orWhereRaw('LOWER(category) LIKE ?', ["%{$search}%"]);
        }

        if (request('category')) {
            $query->where('category', request('category'));
        }

        $tickets = $query->get()->filter(function ($ticket) {
            $available = $ticket->event->totalTickets - $ticket->event->ticketSold;
            return $available > 0;
        });

        return view('pages.admin.orders.create', compact('tickets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|string|in:pending,paid,cancelled,refunded',
            'tickets' => 'required|array|min:1',
            'tickets.*.ticket_id' => 'required|distinct|exists:tickets,id',
            'tickets.*.quantity' => 'required|integer|min:1|max:10',
            'tickets.*.unit_price' => 'required|numeric|min:0',
        ], [
            'tickets.*.ticket_id.distinct' => 'Each ticket may only be selected once.',
            'tickets.*.quantity.max' => 'You may only order up to 10 tickets per item.',
        ]);

        $order = Order::create([
            'user_id' => $validated['user_id'],
            'status' => $validated['status'],
        ]);

        foreach ($validated['tickets'] as $ticketData) {
            $ticket = Ticket::with('event')->findOrFail($ticketData['ticket_id']);
            $event = $ticket->event;

            $available = $event->totalTickets - $event->ticketSold;
            if ($available < $ticketData['quantity']) {
                throw ValidationException::withMessages([
                    'tickets' => ["Not enough tickets available for {$ticket->category} – {$event->title}."]
                ]);
            }

            OrderItem::create([
                'order_id' => $order->id,
                'ticket_id' => $ticket->id,
                'quantity' => $ticketData['quantity'],
                'unit_price' => $ticketData['unit_price'],
                'total_price' => $ticketData['quantity'] * $ticketData['unit_price'],
            ]);

            $event->ticketSold += $ticketData['quantity'];
            $event->save();
        }
        $order->updateTotalPrice();

        return redirect()->route('admin.orders.index')->with('success', 'Order created successfully!');
    }

    public function edit(Order $order)
    {
        $order->load(['user', 'orderItems.ticket']);

        $tickets = Ticket::with('event')->get()->filter(function ($ticket) {
            return ($ticket->event->totalTickets - $ticket->event->ticketSold) > 0;
        });

        return view('pages.admin.orders.edit', compact('order', 'tickets'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|string|in:pending,paid,cancelled,refunded',
            'tickets' => 'required|array|min:1',
            'tickets.*.ticket_id' => 'required|distinct|exists:tickets,id',
            'tickets.*.quantity' => 'required|integer|min:1|max:10',
            'tickets.*.unit_price' => 'required|numeric|min:0',
        ], [
            'tickets.*.ticket_id.distinct' => 'Each ticket may only be selected once.',
            'tickets.*.quantity.max' => 'You may only order up to 10 tickets per item.',
        ]);

        foreach ($order->orderItems as $item) {
            $event = $item->ticket->event;
            $event->ticketSold -= $item->quantity;
            $event->save();
        }

        $order->orderItems()->delete();

        $order->update([
            'user_id' => $validated['user_id'],
            'status' => $validated['status'],
        ]);

        foreach ($validated['tickets'] as $ticketData) {
            $ticket = Ticket::with('event')->findOrFail($ticketData['ticket_id']);
            $event = $ticket->event;

            $available = $event->totalTickets - $event->ticketSold;
            if ($available < $ticketData['quantity']) {
                throw ValidationException::withMessages([
                    'tickets' => ["Not enough tickets available for {$ticket->category} – {$event->title}."]
                ]);
            }

            $order->orderItems()->create([
                'ticket_id' => $ticket->id,
                'quantity' => $ticketData['quantity'],
                'unit_price' => $ticketData['unit_price'],
                'total_price' => $ticketData['quantity'] * $ticketData['unit_price'],
            ]);

            $event->ticketSold += $ticketData['quantity'];
            $event->save();
        }

        $order->updateTotalPrice();

        return redirect()->route('orders.index')->with('success', 'Order updated!');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted!');
    }
}
