@extends('layouts.admin')
@section('title', 'Events')


@section('content')
    <div class="flex flex-1 justify-between items-center bg-[#FFEBFA] p-4">
        <h1 class="text-2xl font-bold">Events</h1>
        <a href="{{ route('events.create') }}" class="bg-[#6B4E71] text-white px-4 py-2 rounded">Add Event</a>
    </div>

    <form method="GET" action="{{ route('admin.events') }}" class="w-full max-w-4xl p-4 mx-auto mb-6">
        <div class="flex flex-wrap gap-4 justify-center">
            <input type="date" name="date" value="{{ request('date') }}" class="border p-2 rounded" />
            <input type="text" name="venue" placeholder="Miejsce" value="{{ request('venue') }}"
                class="border p-2 rounded" />
            <select name="type" class="border p-2 rounded">
                <option value="">-- Typ wydarzenia --</option>
                @foreach ($types as $type)
                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                        {{ ucfirst($type) }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="bg-[#6B4E71] text-white px-4 py-2 rounded">Szukaj</button>
            <a href="{{ route('admin.events') }}" class="text-sm text-blue-500 mt-2">Wyczyść filtry</a>
        </div>
    </form>

    <div class="flex flex-1 justify-center flex-wrap gap-4 p-4 sm:p-5">
        @foreach ($events as $event)
            <x-event-card :event="$event" />
        @endforeach
    </div>
@endsection
