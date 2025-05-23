@extends('layouts.user')

@section('title', '{{ $event->title }}')

@section('content')
    <div class="min-h-screen bg-[#FFF7FD] py-10">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
                {{-- Image --}}
                <div class="lg:col-span-1">
                    <div class="rounded-lg overflow-hidden shadow-md">
                        <img src="{{ $event->image }}" alt="{{ $event->title }}"
                            class="w-full h-full object-cover transition-transform duration-700 hover:scale-105">
                    </div>
                </div>

                {{-- Event info --}}
                <div class="lg:col-span-2 flex flex-col justify-between">
                    <div class="flex flex-col gap-8">
                        <div class="flex flex-wrap gap-2">
                            <span class="bg-[#6B4E71] text-white text-xs px-2 py-1 rounded-full">Conference</span>
                            @if ($event->last_minute)
                                <span class="bg-[#FFD6EC] text-black text-xs px-2 py-1 rounded-full">Last Minute</span>
                            @endif
                        </div>

                        <h1 class="text-3xl lg:text-4xl font-bold text-[#3A4454]">{{ $event->title }}</h1>

                        <div class="flex flex-col md:flex-row gap-4 text-sm md:text-base text-[#6B4E71]">
                            <div class="flex items-center gap-2">
                                @svg('heroicon-o-calendar-days', 'h-5 w-5')
                                <span>{{ \Carbon\Carbon::parse($event->date)->format('d.m.Y') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                @svg('heroicon-o-clock', 'h-5 w-5')
                                <span>{{ \Carbon\Carbon::parse($event->time)->format('H:i') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                @svg('heroicon-o-map-pin', 'h-5 w-5')
                                <span>{{ $event->location }}</span>
                            </div>

                        </div>
                        <div class="flex items-center mb-6">
                            <div
                                class="h-12 w-12 flex justify-center items-center rounded-full bg-white border-2 border-[#D7C1D3] mr-3">
                                @svg('heroicon-o-user', 'h-8 w-8 text-[#6B4E71]')
                            </div>
                            <div>
                                <p class="text-sm text-[#6B4E71]/70">Organized by</p>
                                <h3 class="text-lg font-semibold text-[#3A4454]">{{ $event->organizer }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">

                    <h3 class="text-3xl lg:text-4xl font-bold text-[#3A4454] py-6">Description</h3>

                    <article class="prose max-w-none text-[#3A4454]">
                        {!! nl2br(e($event->description)) !!}
                    </article>
                </div>

                {{-- Sidebar --}}
                <div class="lg:col-span-1">
                    <div class="bg-[#FFEBFA] rounded-lg shadow-md p-6 sticky top-4">
                        <h3 class="text-xl font-semibold text-[#3A4454] mb-4">Tickets</h3>

                        @php
                            $remaining = $event->totalTickets - $event->ticketSold;
                            $percentage = round(($event->ticketSold / $event->totalTickets) * 100);
                            $barColor = match (true) {
                                $percentage < 25 => 'bg-red-400',
                                $percentage < 50 => 'bg-yellow-400',
                                $percentage < 75 => 'bg-purple-400',
                                $percentage >= 90 => 'bg-green-400',
                                default => 'bg-[#6B4E71]',
                            };
                        @endphp

                        <div class="space-y-4 text-[#3A4454] text-sm">
                            <div class="flex justify-between">
                                <span>Available</span>
                                <span class="{{ $remaining > 0 ? 'text-green-600' : 'text-red-600' }} font-semibold">
                                    {{ $remaining }} / {{ $event->totalTickets }}
                                </span>
                            </div>

                            <div>
                                <div class="flex justify-between mb-1">
                                    <span>Tickets sold</span>
                                    <span>{{ $percentage }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                    <div class="h-2.5 rounded-full {{ $barColor }}"
                                        style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>

                            <div class="pt-4">
                                <button
                                    class="w-full py-3 px-4 rounded-lg font-medium flex items-center justify-center gap-2 transition-all
                                {{ $remaining > 0 ? 'bg-[#6B4E71] hover:bg-[#593b5c] text-white' : 'bg-gray-400 text-white cursor-not-allowed' }}"
                                    {{ $remaining <= 0 ? 'disabled' : '' }}>
                                    @svg('heroicon-o-ticket', 'h-5 w-5')
                                    {{ $remaining > 0 ? 'Get Tickets' : 'Sold Out' }}
                                </button>
                            </div>

                            @if ($event->last_minute)
                                <div
                                    class="mt-4 p-3 bg-[#FFD6EC] border border-[#FFB4D4] rounded-md text-sm text-[#6B4E71]">
                                    <p class="font-semibold">Last minute event!</p>
                                    <p>This event is happening soon. Get your tickets while they last.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
