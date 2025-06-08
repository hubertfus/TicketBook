<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;

class TicketPurchaseController extends Controller
{
    public function create(Event $event)
    {
        $tickets = $event->tickets()->where('quantity', '>', 0)->get();
        return view('pages.user.events.buy', compact('event', 'tickets'));
    }

    public function store(Request $request, Event $event)
    {
        if (auth()->guest()) {
            return redirect()->route('login')->with('error', 'You must be logged in to complete your purchase.');
        }

        $quantities = $request->input('quantities', []);
        $validatedData = [];
        $totalPrice = 0;

        foreach ($quantities as $ticketId => $quantity) {
            $quantity = (int) $quantity;

            if ($quantity > 0) {
                $ticket = $event->tickets()->where('id', $ticketId)->first();

                if (!$ticket) {
                    return back()->withErrors(['quantities.' . $ticketId => 'Invalid ticket selected.'])->withInput();
                }

                if ($quantity > $ticket->quantity) {
                    return back()->withErrors(['quantities.' . $ticketId => 'You cannot buy more tickets than available.'])->withInput();
                }

                $validatedData[] = [
                    'ticket_id' => $ticket->id,
                    'category' => $ticket->category,
                    'quantity' => $quantity,
                    'price' => $ticket->price,
                    'subtotal' => $ticket->price * $quantity,
                ];

                $totalPrice += $ticket->price * $quantity;
            }
        }

        if (empty($validatedData)) {
            return back()->withErrors(['quantities' => 'Please select at least one ticket.'])->withInput();
        }

        session()->put('purchase_data', [
            'event_id' => $event->id,
            'tickets' => $validatedData,
            'total' => $totalPrice,
        ]);

        return redirect()->route('payment.show', ['event' => $event->id])
            ->with('info', 'Redirected to payment');
    }

}
