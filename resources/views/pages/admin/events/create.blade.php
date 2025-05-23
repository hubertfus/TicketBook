@extends('layouts.admin')

@section('content')
    @if ($errors->any())
        <div class="bg-red-100 text-red-600 p-4 mb-4 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-xl mx-auto  my-8">
        <h2 class="text-2xl font-bold mb-4">Add New Event</h2>

        <form action="{{ route('events.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block">Title</label>
                <input type="text" name="title" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block">Date</label>
                <input type="date" name="date" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block">Time</label>
                <input type="time" name="time" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block">Type</label>
                <select name="type" class="w-full border rounded p-2" required>
                    <option value="concert">Koncert</option>
                    <option value="sport">Sport</option>
                    <option value="standup">Stand-up</option>
                    <option value="festival">Festival</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block">Description</label>
                <textarea name="description" class="w-full border rounded p-2" required></textarea>
            </div>
            <div class="mb-4">
                <label class="block">Image URL</label>
                <input type="text" name="image" class="w-full border rounded p-2">
            </div>
            <div class="mb-4">
                <label class="block">Total Tickets</label>
                <input type="number" name="totalTickets" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block">Tickets Sold</label>
                <input type="number" name="ticketSold" class="w-full border rounded p-2" value="0">
            </div>
            <div class="mb-4">
                <label class="block">Organizer</label>
                <input type="text" name="organizer" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block">Location</label>
                <input type="text" name="location" class="w-full border rounded p-2" required>
            </div>
            <div class="flex flex-row gap-4">
                <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded">Save Event</button>
                <a href="{{ route('events.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded">Cancel</a>
            </div>
        </form>
    </div>
@endsection
