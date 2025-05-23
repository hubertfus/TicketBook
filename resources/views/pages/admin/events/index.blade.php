@extends('layouts.admin')
@section('title', 'Events')


@section('content')
    <div class="flex flex-1 justify-between items-center bg-[#FFEBFA] p-4">
        <h1 class="text-2xl font-bold">Events</h1>
        <a href="{{ route('events.create') }}" class="bg-[#6B4E71] text-white px-4 py-2 rounded">Add Event</a>
    </div>

    <div class="flex flex-1 justify-center flex-wrap gap-4 p-4 sm:p-5">
        @foreach ($events as $event)
            <x-event-card :event="$event" />
        @endforeach
    </div>
@endsection
