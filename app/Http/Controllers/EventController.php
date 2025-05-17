<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{

    public function index(Request $request)
    {
        $query = Event::query();

        if ($request->filled('date')) {
            $query->whereDate('date', $request->input('date'));
        }

        if ($request->filled('venue')) {
            $query->where('venue', 'like', '%' . $request->input('venue') . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        $events = $query->latest()->paginate(10);
        $types = Event::select('type')->distinct()->pluck('type');

        return view('admin.events', compact('events', 'types'));
    }
    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
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
            'status' => 'required|string',
            'venue' => 'required|string',
        ]);
        var_dump($validated);
        Event::create($validated);

        return redirect()->route('admin.events')->with('success', 'Event added!');
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
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
            'status' => 'required|string',
            'venue' => 'required|string',
        ]);

        $event->update($validated);

        return redirect()->route('admin.events')->with('success', 'Event updated!');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events')->with('success', 'Event deleted!');
    }
}
