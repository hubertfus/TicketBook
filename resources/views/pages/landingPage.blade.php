@extends('layouts.user')

@section('title', 'Home')

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
    <div class="relative flex justify-center flex-col lg:flex-row h-full bg-[#FFF7FD] lg:bg-transparent">
        <div class=" px-4 sm:px-6 lg:px-0 flex flex-col lg:flex-row gap-10 lg:gap-20 py-10">
            <div class="lg:w-1/2 flex flex-col justify-center space-y-6 text-center lg:text-left relative z-10">
                <small class="text-gray-500 font-medium text-sm md:text-base">
                    Entertainment at your fingertips – find something for yourself
                </small>

                <h1 class="text-3xl md:text-5xl font-bold text-gray-900">
                    Discover the best events in your city
                </h1>

                <p class="text-gray-600 text-base md:text-lg">
                    Choose from concerts, performances, festivals, and many more attractions.
                    Buy tickets quickly, conveniently, and without leaving home.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                    <a href="#"
                        class="bg-[#6B4E71] text-lg sm:text-xl text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#48354D] transition">
                        Buy a ticket →
                    </a>
                    <a href="#" class="text-[#6B4E71] text-lg sm:text-xl font-semibold underline hover:no-underline">
                        Learn more
                    </a>
                </div>
            </div>

            <div class="hidden lg:block lg:w-1/2 absolute lg:h-full right-0">
                <img src="images/ticket.jpg" alt="Tickets"
                    class="absolute left-0 w-full h-full object-cover rounded-l-[4rem] top-16" />
            </div>
        </div>
    </div>

    <div class="w-full relative z-20" style="background: linear-gradient(to bottom, transparent 50%, #FFEBFA 50%);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
            <div class="w-full relative z-20">
                <x-searchbar :filters="$filters" :action="route('user.events.index')" />
            </div>
        </div>
    </div>

    <div class="w-full bg-[#FFEBFA] py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <h1 class="text-3xl md:text-5xl font-bold text-black text-center mb-6">Concerts you can't miss</h1>
            <small class="block text-sm md:text-base font-bold text-gray-500 text-center mb-8">
                Discover events you must experience live
            </small>
            @include('components.carousel', [
                'slides' => [
                    ['image' => 'images/ticket.jpg', 'alt' => 'Slide 1'],
                    ['image' => 'images/ticket.jpg', 'alt' => 'Slide 2'],
                    ['image' => 'images/ticket.jpg', 'alt' => 'Slide 3'],
                    ['image' => 'images/ticket.jpg', 'alt' => 'Slide 4'],
                    ['image' => 'images/ticket.jpg', 'alt' => 'Slide 5'],
                    ['image' => 'images/ticket.jpg', 'alt' => 'Slide 6'],
                ],
            ])
        </div>
    </div>
@endsection
