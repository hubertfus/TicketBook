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
        ]);

        $exists = $event->tickets()->whereRaw('LOWER(category) = ?', [strtolower($validated['category'])])->exists();

        if ($exists) {
            return back()->withErrors([
                'category' => 'This category already exists for this event.',
            ])->withInput();
        }

        $event->tickets()->create($validated);

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
        return view('pages.admin.tickets.edit', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:5',
        ]);

        $exists = Ticket::where('event_id', $ticket->event_id)
            ->whereRaw('LOWER(category) = ?', [strtolower($validated['category'])])
            ->where('id', '!=', $ticket->id)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'category' => 'This category already exists for this event.',
            ])->withInput();
        }

        $ticket->update($validated);

        return redirect()
            ->route('tickets.byEvent', $ticket->event_id)
            ->with('success', 'Ticket updated.');
    }

    public function destroy(Ticket $ticket)
    {
        $event = $ticket->event;

        if ($ticket->orderItems()->exists()) {
            return back()->withErrors([
                'ticket' => 'Cannot delete this category — there are sold tickets.'
            ]);
        }

        $ticket->delete();

        return redirect()
            ->route('tickets.byEvent', $event->id)
            ->with('success', 'Ticket category deleted.');
    }
}
