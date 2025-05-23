<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{

    public function index(Request $request)
    {
        $events = Event::all();

        return view('pages.admin.events.index', compact('events'));
    }
    public function create()
    {
        return view('pages.admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'date' => 'required',
            'time' => 'required',
            'type' => 'required|string',
            'description' => 'required',
            'image' => 'nullable|string',
            'totalTickets' => 'required|integer',
            'ticketSold' => 'nullable|integer',
            'location' => 'required|string',
            'organizer' => 'required|string'
        ]);
        Event::create($validated);

        return redirect()->route('events.index')->with('success', 'Event added!');
    }

    public function edit(Event $event)
    {
        return view('pages.admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
            'type' => 'required|string',
            'description' => 'required',
            'image' => 'nullable|string',
            'totalTickets' => 'required|integer',
            'ticketSold' => 'nullable|integer',
            'location' => 'required|string',
            'organizer' => 'required|string'
        ]);

        $event->update($validated);

        return redirect()->route('events.index')->with('success', 'Event updated!');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted!');
    }
}
