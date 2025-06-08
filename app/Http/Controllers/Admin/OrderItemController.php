<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OrderItemController extends Controller
{
    public function create(Request $request)
    {
        $order = Order::findOrFail($request->input('order_id'));
        $tickets = Ticket::where('quantity', '>', 0)->get();

        return view('pages.admin.order-items.create', compact('order', 'tickets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'tickets' => 'required|array|min:1',
            'tickets.*.ticket_id' => 'required|distinct|exists:tickets,id',
            'tickets.*.quantity' => 'required|integer|min:1|max:10',
            'tickets.*.unit_price' => 'required|numeric|min:0',
        ], [
            'tickets.*.ticket_id.distinct' => 'Each ticket may only be selected once.',
            'tickets.*.quantity.max' => 'You may only order up to 10 tickets per item.',
        ]);

        $order = Order::findOrFail($validated['order_id']);

        foreach ($validated['tickets'] as $ticketData) {
            $ticket = Ticket::findOrFail($ticketData['ticket_id']);

            if ($ticket->quantity < $ticketData['quantity']) {
                throw ValidationException::withMessages([
                    'tickets' => ["Not enough tickets available for {$ticket->category} – {$ticket->event->title}."]
                ]);
            }

            OrderItem::create([
                'order_id' => $order->id,
                'ticket_id' => $ticket->id,
                'quantity' => $ticketData['quantity'],
                'unit_price' => $ticketData['unit_price'],
                'total_price' => $ticketData['quantity'] * $ticketData['unit_price'],
            ]);

            $ticket->decrement('quantity', $ticketData['quantity']);
        }

        $order->updateTotalPrice();

        return redirect()->route('orders.edit', $order)->with('success', 'Tickets added to the order successfully!');
    }

    public function edit(OrderItem $orderItem)
    {
        $tickets = Ticket::where('quantity', '>', 0)->orWhere('id', $orderItem->ticket_id)->get();


        return view('pages.admin.order-items.edit', compact('orderItem', 'tickets'));
    }

    public function update(Request $request, OrderItem $orderItem)
    {
        $validated = $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'quantity' => 'required|integer|min:1|max:10',
            'unit_price' => 'required|numeric|min:0',
        ], [
            'quantity.max' => 'You may only order up to 10 tickets per item.',
        ]);

        $ticket = Ticket::findOrFail($validated['ticket_id']);
        $oldQuantity = $orderItem->quantity;

        $quantityDiff = $validated['quantity'] - $oldQuantity;

        if ($quantityDiff > 0 && $ticket->quantity < $quantityDiff) {
            throw ValidationException::withMessages([
                'quantity' => ["Not enough tickets available for {$ticket->category} – {$ticket->event->title}."]
            ]);
        }

        $orderItem->update([
            'ticket_id' => $validated['ticket_id'],
            'quantity' => $validated['quantity'],
            'unit_price' => $validated['unit_price'],
            'total_price' => $validated['quantity'] * $validated['unit_price'],
        ]);

        if ($quantityDiff != 0) {
            $ticket->decrement('quantity', max(0, $quantityDiff));
            if ($quantityDiff < 0) {
                $ticket->increment('quantity', abs($quantityDiff));
            }
        }

        $orderItem->order->updateTotalPrice();

        return redirect()->route('orders.edit', $orderItem->order_id)->with('success', 'Item updated successfully!');
    }

    public function destroy(OrderItem $orderItem)
    {
        $order = $orderItem->order;
        $ticket = $orderItem->ticket;

        $ticket->increment('quantity', $orderItem->quantity);

        $orderItem->delete();
        $order->updateTotalPrice();

        return redirect()->route('orders.edit', $order->id)->with('success', 'Item deleted successfully!');
    }
}
