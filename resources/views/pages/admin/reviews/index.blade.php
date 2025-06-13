@extends('layouts.admin')
@section('title', 'Reviews')

@php
    $filters = [
        [
            'name' => 'search',
            'type' => 'text',
            'placeholder' => 'Search',
            'icon' => 'heroicon-o-magnifying-glass',
        ],
        [
            'name' => 'rating',
            'type' => 'select',
            'label' => 'Rating',
            'options' => $ratings,
            'icon' => 'heroicon-o-star',
        ],
        [
            'name' => 'event',
            'type' => 'select',
            'label' => 'Event',
            'options' => $events->pluck('title', 'id')->toArray(),
            'icon' => 'heroicon-o-calendar',
        ],
    ];
@endphp

@section('content')
    <div class="min-h-screen bg-[#FFF7FD] py-6 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="bg-[#FFEBFA] rounded-2xl shadow-lg p-6 mb-6">
                <h1 class="text-3xl font-extrabold text-[#3A4454] flex items-center gap-2">
                    @svg('heroicon-o-chat-bubble-bottom-center-text', 'w-8 h-8 text-[#6B4E71]')
                    Reviews
                </h1>
            </div>

            <div class="mb-6">
                <x-searchbar :filters="$filters" :action="route('admin.reviews.index')" />
            </div>

            <div class="bg-[#FFEBFA] rounded-2xl shadow-lg p-6 mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-lg shadow-sm border border-[#D7C1D3]">
                    <div class="text-[#6B4E71] flex items-center gap-2">
                        @svg('heroicon-o-chat-bubble-bottom-center-text', 'h-6 w-6')
                        <span class="font-medium">Total reviews</span>
                    </div>
                    <div class="text-2xl font-bold text-[#3A4454] mt-2">{{ $totalReviews }}</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-[#D7C1D3]">
                    <div class="text-[#6B4E71] flex items-center gap-2">
                        @svg('heroicon-o-star', 'h-6 w-6')
                        <span class="font-medium">Average rating</span>
                    </div>
                    <div class="text-2xl font-bold text-[#3A4454] mt-2">{{ number_format($averageRating, 1) }}/5</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-[#D7C1D3]">
                    <div class="text-[#6B4E71] flex items-center gap-2">
                        @svg('heroicon-o-calendar', 'h-6 w-6')
                        <span class="font-medium">Last review</span>
                    </div>
                    <div class="text-2xl font-bold text-[#3A4454] mt-2">{{ $lastReviewDate?->format('d.m.Y') ?? 'None' }}
                    </div>
                </div>
            </div>

            <div class="bg-[#FFEBFA] rounded-2xl shadow-lg p-6">
                @if ($reviews->isEmpty())
                    <div class="text-center py-10">
                        @svg('heroicon-o-chat-bubble-oval-left-ellipsis', 'w-12 h-12 mx-auto text-[#6B4E71]/50')
                        <p class="mt-4 text-lg text-[#3A4454]">No reviews found</p>
                        <p class="text-[#6B4E71]/70">Try changing your search criteria</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($reviews as $review)
                            <div
                                class="bg-white p-5 rounded-xl shadow-sm border border-[#D7C1D3] hover:shadow-md transition">
                                <div class="flex justify-between items-start">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full bg-[#FFEBFA] flex items-center justify-center">
                                            @svg('heroicon-o-user', 'h-5 w-5 text-[#6B4E71]')
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-[#3A4454]">{{ $review->user->name }}</h3>
                                            <div class="flex items-center gap-1 text-sm text-[#6B4E71]">
                                                @svg('heroicon-o-calendar', 'h-4 w-4')
                                                {{ $review->created_at->format('d.m.Y H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.reviews.edit', $review) }}"
                                            class="p-2 text-[#6B4E71] hover:bg-[#FFEBFA] rounded-full" title="Edit">
                                            @svg('heroicon-o-pencil', 'h-5 w-5')
                                        </a>
                                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-full"
                                                title="Delete" onclick="return confirm('Delete this review?')">
                                                @svg('heroicon-o-trash', 'h-5 w-5')
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="mt-4 pl-13">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="font-medium text-[#3A4454]">Event:</span>
                                        <a href="{{ route('events.show', $review->event) }}"
                                            class="text-[#6B4E71] hover:underline font-medium">
                                            {{ $review->event->title }}
                                        </a>
                                    </div>

                                    <div class="flex items-center gap-1 mb-3">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $review->rating)
                                                <span class="text-yellow-400">
                                                    @svg('heroicon-s-star', 'h-5 w-5 fill-current')
                                                </span>
                                            @else
                                                <span class="text-gray-300">
                                                    @svg('heroicon-s-star', 'h-5 w-5 fill-current')
                                                </span>
                                            @endif
                                        @endfor
                                        <span class="ml-1 text-sm text-[#6B4E71]">{{ $review->rating }}/5</span>
                                    </div>
                                    @if ($review->hasComment())
                                        <div class="bg-[#FFF7FD] p-3 rounded-lg">
                                            <p class="text-[#3A4454] whitespace-pre-line">{{ $review->comment }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($reviews->hasPages())
                        <div class="mt-8 pt-6 border-t border-[#6B4E71]/20">
                            {{ $reviews->withQueryString()->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection
