@extends('layouts.user')

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
    <div class="flex flex-col items-center flex-1">
        <div class="max-w-7xl">
            <div class="w-full relative z-20">
                <div class="max-w-7xl mx-auto  p-4 sm:p-5">
                    <div class="w-full relative z-20">
                        <x-searchbar :filters="$filters" :action="route('user.events.index')" />
                    </div>
                </div>
            </div>
            @auth
                @if (isset($suggestedEvents) && !$suggestedEvents->isEmpty() && !$isAdmin)
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">Recommended for you</h2>
                    <div class="flex flex-1 justify-start flex-wrap gap-4 p-4 sm:p-5">
                        @foreach ($suggestedEvents as $event)
                            <x-event-card :event="$event" />
                        @endforeach
                    </div>
                @endif
            @endauth

            <h2 class="text-2xl font-bold mb-6 text-gray-800">All events</h2>

            @if ($events->isEmpty())
                <div class="flex justify-center items-center p-10">
                    <p class="text-xl text-gray-600">No events found.</p>
                </div>
            @else
                <div class="flex flex-1 justify-center flex-wrap gap-4 p-4 sm:p-5">
                    @foreach ($events as $event)
                        <x-event-card :event="$event" />
                    @endforeach
                </div>

                <div class="w-full flex justify-center mt-6 py-6">
                    <div class="max-w-sm sm:flex sm:flex-col">
                        {{ $events->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
