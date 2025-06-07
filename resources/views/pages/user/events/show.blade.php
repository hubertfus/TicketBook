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
                            $eventEnded = \Carbon\Carbon::parse($event->date)->isPast();
                            $canBuy = $remaining > 0 && !$eventEnded;
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

                            <a href="{{ $canBuy ? route('tickets.buy', $event) : '#' }}"
                                class="w-full py-3 px-4 rounded-lg font-medium flex items-center justify-center gap-2 transition-all
                                {{ $canBuy ? 'bg-[#6B4E71] hover:bg-[#593b5c] text-white' : 'bg-gray-400 text-white cursor-not-allowed' }}"
                                {{ !$canBuy ? 'aria-disabled=true tabindex=-1' : '' }}>
                                @svg('heroicon-o-ticket', 'h-5 w-5')
                                {{ $canBuy ? 'Get Tickets' : ($eventEnded ? 'Event ended' : 'Sold Out') }}
                            </a>
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

            {{-- REVIEWS SECTION --}}
            @if (\Carbon\Carbon::parse($event->date)->isPast())
                <div class="lg:col-span-4 mt-12">
                    <h2 class="text-2xl font-semibold text-[#3A4454] mb-6">User Reviews</h2>
                    @auth

                        <div class="mt-10 p-6 bg-white rounded-2xl shadow-md w-full mb-10">
                            <h3 class="text-xl font-semibold mb-4 text-[#3A4454]">Add Your Review</h3>

                            @if (session('success'))
                                <div class="mb-4 text-green-600">{{ session('success') }}</div>
                            @endif
                            @if (session('error'))
                                <div class="mb-4 text-red-600">{{ session('error') }}</div>
                            @endif

                            <form action="{{ route('reviews.store', $event) }}" method="POST" class="space-y-4">
                                @csrf

                                {{-- Rating --}}
                                <div class="flex flex-row-reverse justify-end space-x-0 space-x-reverse">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <input type="radio" id="star{{ $i }}" name="rating"
                                            value="{{ $i }}" class="hidden peer" />
                                        <label for="star{{ $i }}"
                                            class="text-3xl text-gray-300 peer-checked:text-yellow-400 cursor-pointer transition hover:scale-110">
                                            &#9733;
                                        </label>
                                    @endfor
                                </div>
                                {{-- Comment --}}
                                <div>
                                    <label for="comment" class="block mb-2 font-medium text-[#6B4E71]">Comment
                                        (optional)
                                    </label>
                                    <textarea id="comment" name="comment" rows="4" maxlength="1000"
                                        class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#6B4E71]"
                                        placeholder="Write your review here...">{{ old('comment') }}</textarea>
                                </div>

                                <button type="submit"
                                    class="bg-[#6B4E71] hover:bg-[#593b5c] text-white py-2 px-6 rounded-md font-semibold transition">
                                    Submit Review
                                </button>
                            </form>
                        </div>
                    @endauth
                    @if ($reviews->count() > 0)
                        <div class="space-y-6">
                            @foreach ($reviews as $review)
                                <div class="bg-white p-6 rounded-2xl shadow-inner">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-10 h-10 bg-[#6B4E71] text-white rounded-full flex items-center justify-center font-bold">
                                                {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-[#3A4454]">
                                                    {{ $review->user->name ?? 'Unknown' }}</p>
                                                <p class="text-xs text-[#6B4E71]/70">
                                                    {{ $review->created_at->format('d.m.Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1 text-yellow-400">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $review->rating)
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 fill-current"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.955a1 1 0 00.95.69h4.167c.969 0 1.371 1.24.588 1.81l-3.374 2.455a1 1 0 00-.364 1.118l1.287 3.955c.3.921-.755 1.688-1.538 1.118l-3.374-2.455a1 1 0 00-1.175 0l-3.374 2.455c-.783.57-1.838-.197-1.538-1.118l1.287-3.955a1 1 0 00-.364-1.118L2.068 9.382c-.783-.57-.38-1.81.588-1.81h4.167a1 1 0 00.95-.69l1.286-3.955z" />
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 fill-gray-300"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.955a1 1 0 00.95.69h4.167c.969 0 1.371 1.24.588 1.81l-3.374 2.455a1 1 0 00-.364 1.118l1.287 3.955c.3.921-.755 1.688-1.538 1.118l-3.374-2.455a1 1 0 00-1.175 0l-3.374 2.455c-.783.57-1.838-.197-1.538-1.118l1.287-3.955a1 1 0 00-.364-1.118L2.068 9.382c-.783-.57-.38-1.81.588-1.81h4.167a1 1 0 00.95-.69l1.286-3.955z" />
                                                    </svg>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                    @if ($review->comment)
                                        <p class="text-[#3A4454]">{{ $review->comment }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $reviews->links() }}
                        </div>
                    @else
                        <p class="text-[#6B4E71]/70">No reviews yet. Be the first to leave a review!</p>
                    @endif
            @endif
        </div>

    </div>
    </div>
@endsection
