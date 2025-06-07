@extends('layouts.user')

@section('title', $event->title)

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
    <div class="min-h-screen bg-[#FFF7FD] py-10">
        <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-4 gap-8 px-4">
            {{-- IMAGE SECTION --}}
            <div class="lg:col-span-2 flex flex-col justify-center">
                <div class="relative rounded-2xl overflow-hidden shadow-lg">
                    @if ($event->image && Storage::disk('public')->exists($event->image))
                        <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}"
                            class="w-full h-96 object-cover transform transition hover:scale-105">
                    @else
                        <img src="{{ asset('images/placeholder.jpg') }}" alt="{{ $event->title }}"
                            class="w-full h-96 object-cover transform transition hover:scale-105">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-6">
                        <h1 class="text-3xl lg:text-4xl font-bold text-white drop-shadow-lg">{{ $event->title }}</h1>
                    </div>
                </div>
            </div>

            {{-- INFO & TAGS --}}
            <div class="lg:col-span-2 flex flex-col justify-between space-y-6">
                <div class="flex flex-wrap gap-3">
                    <span
                        class="px-4 py-1 bg-[#6B4E71] text-white text-xs font-semibold rounded-full">{{ ucfirst($event->type) }}</span>
                    @if ($event->last_minute)
                        <span class="px-4 py-1 bg-[#FFD6EC] text-[#3A4454] text-xs font-semibold rounded-full">Last
                            Minute</span>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-[#6B4E71]">
                    <div class="flex items-center gap-2">
                        @svg('heroicon-o-calendar-days', 'h-5 w-5')
                        <time>{{ \Carbon\Carbon::parse($event->date)->format('d.m.Y') }}</time>
                    </div>
                    <div class="flex items-center gap-2">
                        @svg('heroicon-o-clock', 'h-5 w-5')
                        <time>{{ \Carbon\Carbon::parse($event->time)->format('H:i') }}</time>
                    </div>
                    <div class="flex items-center gap-2">
                        @svg('heroicon-o-map-pin', 'h-5 w-5')
                        <span>{{ $event->location }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white rounded-full shadow-inner flex items-center justify-center">
                        @svg('heroicon-o-user', 'h-7 w-7 text-[#6B4E71]')
                    </div>
                    <div>
                        <p class="text-sm text-[#6B4E71]/60 uppercase">Organized by</p>
                        <h3 class="text-lg font-semibold text-[#3A4454]">{{ $event->organizer }}</h3>
                    </div>
                </div>

                <div class="hidden lg:block">
                    <aside class="bg-[#FFEBFA] p-6 rounded-2xl shadow-lg sticky top-24">
                        <h2 class="text-xl font-semibold text-[#3A4454] mb-4">Tickets</h2>
                        @php
                            $remaining = $event->totalTickets - $event->ticketSold;
                            $percent = round(($event->ticketSold / $event->totalTickets) * 100);
                        @endphp
                        <div class="space-y-4 text-[#3A4454] text-sm">
                            <div class="flex justify-between">
                                <span>Available</span>
                                <span
                                    class="font-semibold {{ $remaining > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $remaining }}
                                    / {{ $event->totalTickets }}</span>
                            </div>
                            <div>
                                <div class="flex justify-between mb-1 text-sm">
                                    <span>Sold</span>
                                    <span>{{ $percent }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="h-2.5 rounded-full bg-[#6B4E71]" style="width:{{ $percent }}%"></div>
                                </div>
                            </div>
                            <button
                                class="w-full py-3 px-4 rounded-lg font-medium flex items-center justify-center gap-2 transition-all
                                {{ $remaining > 0 ? 'bg-[#6B4E71] hover:bg-[#593b5c] text-white' : 'bg-gray-400 text-white cursor-not-allowed' }}"
                                {{ $remaining <= 0 ? 'disabled' : '' }}>
                                @svg('heroicon-o-ticket', 'h-5 w-5')
                                {{ $remaining > 0 ? 'Get Tickets' : 'Sold Out' }}
                            </button>
                            @if ($event->last_minute)
                                <div class="mt-4 p-3 bg-[#FFD6EC] border border-[#FFB4D4] rounded-lg text-[#6B4E71]">
                                    <p class="font-semibold mb-1">Last minute!</p>
                                    <p class="text-sm">Grab your ticket before it’s gone.</p>
                                </div>
                            @endif
                        </div>
                    </aside>
                </div>
            </div>

            {{-- DESCRIPTION SECTION --}}
            <div class="lg:col-span-4 mt-8">
                <div class="bg-white p-8 rounded-2xl shadow-inner prose prose-lg text-[#3A4454]">
                    {!! nl2br(e($event->description)) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
