@extends('layouts.admin')
@section('title', 'Events')

@php
    $filters = [
        [
            'name' => 'title',
            'type' => 'text',
            'placeholder' => 'Title',
            'icon' => 'heroicon-o-information-circle',
        ],
        [
            'name' => 'location',
            'type' => 'text',
            'placeholder' => 'City',
            'icon' => 'heroicon-o-map-pin',
        ],
        [
            'name' => 'date',
            'type' => 'date',
            'icon' => 'heroicon-o-calendar',
        ],
        [
            'name' => 'type',
            'type' => 'select',
            'label' => 'Event type',
            'options' => ['concert', 'sport', 'standup', 'festival', 'other'],
            'icon' => 'heroicon-o-tag',
        ],
    ];
@endphp

@section('content')
    <div class="flex flex-1 justify-between items-center bg-[#FFEBFA] p-4">
        <h1 class="text-2xl font-bold">Events</h1>
        <a href="{{ route('events.create') }}" class="bg-[#6B4E71] text-white px-4 py-2 rounded">Add Event</a>
    </div>

    <div class="w-full relative z-20">
        <div class="max-w-7xl mx-auto  p-4 sm:p-5">
            <div class="w-full relative z-20">
                <x-searchbar :filters="$filters" :action="route('events.index')" />
            </div>
        </div>
    </div>

    <div class="flex flex-1 justify-center flex-wrap gap-4 p-4 sm:p-5">
        @foreach ($events as $event)
            <x-event-card :event="$event" />
        @endforeach
    </div>
@endsection
