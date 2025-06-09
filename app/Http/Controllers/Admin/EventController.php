<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

        if ($request->filled('location')) {
            $location = strtolower($request->input('location'));
            $query->whereRaw('LOWER(location) LIKE ?', ["%{$location}%"]);
        }

        if ($request->filled('title')) {
            $title = strtolower($request->input('title'));
            $query->whereRaw('LOWER(title) LIKE ?', ["%{$title}%"]);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        $events = $query->latest()->paginate(10);
        $types  = Event::select('type')->distinct()->pluck('type');

        return view('pages.admin.events.index', compact('events', 'types'));
    }

    public function create()
    {
        return view('pages.admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:5|max:255',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'type' => 'required|string|in:concert,sport,standup,festival,other',
            'description' => 'required|string|min:10|max:2000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'totalTickets' => 'required|integer|min:1',
            'ticketSold' => 'nullable|integer|min:0|max:' . ($request->input('totalTickets') ?? '0'),
            'location' => 'required|string|min:3|max:255',
            'organizer' => 'required|string|min:3|max:255',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        Event::create($validated);

        return redirect()->route('events.index')
            ->with('success', 'Event added!');
    }

    public function edit(Event $event)
    {
        return view('pages.admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:5|max:255',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'type' => 'required|string|in:concert,sport,standup,festival,other',
            'description' => 'required|string|min:10|max:2000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'totalTickets' => 'required|integer|min:1',
            'ticketSold' => 'nullable|integer|min:0|max:' . ($request->input('totalTickets') ?? $event->totalTickets),
            'location' => 'required|string|min:3|max:255',
            'organizer' => 'required|string|min:3|max:255',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($validated);

        return redirect()->route('events.index')
            ->with('success', 'Event updated!');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event deleted!');
    }
}
