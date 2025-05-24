<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $types = Event::select('type')->distinct()->pluck('type');


        if (Auth::check() && Auth::user()->role === 'admin')
            return view('pages.admin.events.index', compact('events', 'types'));

        return view('pages.user.events.index', compact('events', 'types'));
    }

    public function show(Event $event)
    {
        return view('pages.user.events.show', compact('event'));
    }

    public function create()
    {
        if (Auth::check() && Auth::user()->role === 'admin')
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
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'totalTickets' => 'required|integer',
            'ticketSold' => 'nullable|integer',
            'location' => 'required|string',
            'organizer' => 'required|string'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        Event::create($validated);

        if (Auth::check() && Auth::user()->role === 'admin')
            return redirect()->route('events.index')->with('success', 'Event added!');
    }

    public function edit(Event $event)
    {
        if (Auth::check() && Auth::user()->role === 'admin')
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
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'totalTickets' => 'required|integer',
            'ticketSold' => 'nullable|integer',
            'location' => 'required|string',
            'organizer' => 'required|string'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($validated);

        if (Auth::check() && Auth::user()->role === 'admin')
            return redirect()->route('events.index')->with('success', 'Event updated!');
    }

    public function destroy(Event $event)
    {
        if (Auth::check() && Auth::user()->role === 'admin')
            $event->delete();
        return redirect()->route('events.index')->with('success', 'Event deleted!');
    }
}
