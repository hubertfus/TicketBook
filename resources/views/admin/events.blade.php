@extends('layouts.admin')
@section('title', 'Events')
<?php
$eventsMockup = \App\Models\Event::all();
?>

@section('content')
    <div class="fixed top-0 left-0 w-full bg-[#FFEBFA] p-4">
        <div class="flex ml-24 lg:ml-64 justify-between items-center">
            <h1 class="text-2xl font-bold">Events</h1>
            <a href="{{ route('events.create') }}" class="bg-[#6B4E71] text-white px-4 py-2 rounded">Add Event</a>
        </div>
    </div>
    <div class="flex flex-1 justify-center flex-wrap m-24 gap-4 p-4 sm:p-5">
        @foreach ($eventsMockup as $event)
            <x-event-card :event="$event" />
        @endforeach
    </div>
@endsection
