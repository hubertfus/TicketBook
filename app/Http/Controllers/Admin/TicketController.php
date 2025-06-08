<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Event;
use Illuminate\Http\Request;

class TicketController extends Controller
{
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

        $ticket->update($validated);

        return redirect()->route('tickets.index')->with('success', 'Ticket updated successfully!');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully!');
    }
}
