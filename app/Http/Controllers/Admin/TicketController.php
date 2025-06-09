<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Event;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function create(Event $event)
    {
        return view('pages.admin.tickets.create', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:5',
            'quantity' => 'required|integer|min:1',
        ]);

        $event->tickets()->create($validated);

        $totalInCategories = $event->tickets()->sum('quantity');

        if ($totalInCategories > $event->totalTickets) {
            $event->totalTickets = $totalInCategories;
            $event->save();
        }

        return redirect()
            ->route('tickets.byEvent', $event->id)
            ->with('success', 'New ticket category added successfully.');
    }

    public function byEvent(Request $request, Event $event)
    {
        $query = $event->tickets();

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('price')) {
            $query->where('price', '<=', $request->input('price'));
        }

        $tickets = $query->paginate(10);

        return view('pages.admin.tickets.by-event', compact('event', 'tickets'));
    }


    public function edit(Ticket $ticket)
    {
        $events = Event::all();
        return view('pages.admin.tickets.edit', compact('ticket', 'events'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'category' => 'required|in:standard,vip,student',
            'price' => 'required|numeric|min:5',
            'quantity' => 'required|integer|min:0',
        ]);
        $event = $ticket->event;

        $sold = $event->ticketSold;
        $oldQty = $ticket->quantity;
        $newQty = $validated['quantity'];
        $delta = $newQty - $oldQty;

        $newTotal = $event->totalTickets + $delta;

        if ($newTotal < $sold) {
            return back()->withErrors([
                'quantity' => "Cannot set quantity to {$newQty}; total tickets ({$newTotal}) would be less than sold ({$sold})."
            ])->withInput();
        }

        $ticket->update($validated);

        $event->totalTickets = $newTotal;
        $event->save();

        return redirect()
            ->route('tickets.byEvent', $event->id)
            ->with('success', 'Ticket updated and totalTickets adjusted.');
    }

    public function destroy(Ticket $ticket)
    {
        $event = $ticket->event;
        $sold = $event->ticketSold;

        $qty = $ticket->quantity;
        $newTotal = $event->totalTickets - $qty;

        if ($newTotal < $sold) {
            return back()->withErrors([
                'ticket' => "Cannot delete category; total tickets ({$newTotal}) would be less than sold ({$sold})."
            ]);
        }

        $ticket->delete();
        $event->totalTickets = $newTotal;
        $event->save();

        return redirect()
            ->route('tickets.byEvent', $event->id)
            ->with('success', 'Ticket category deleted and totalTickets updated!');
    }
}
