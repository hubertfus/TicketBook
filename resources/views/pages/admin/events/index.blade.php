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
        <a href="{{ route('admin.events.create') }}" class="bg-[#6B4E71] text-white px-4 py-2 rounded">Add Event</a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 text-red-600 p-4 mb-4 rounded shadow-inner">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-4 mb-4 rounded shadow-inner">
            {{ session('success') }}
        </div>
    @endif

    <div class="w-full relative z-20">
        <div class="flex flex-col flex-1 gap-6 max-w-7xl mx-auto  p-4 sm:p-5">
            <div class="w-full relative z-20">
                <x-searchbar :filters="$filters" :action="route('admin.events.index')" />
            </div>

            <div class="flex flex-1 justify-center items-center flex-wrap gap-4 ">
                @foreach ($events as $event)
                    <x-event-card :event="$event" />
                @endforeach
            </div>
        </div>
    </div>


    <div class="w-full flex justify-center mt-6 py-6">
        <div class="max-w-sm sm:flex sm:flex-col">
            {{ $events->withQueryString()->links() }}
        </div>
    </div>
@endsection
