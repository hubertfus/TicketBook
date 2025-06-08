@php
    use Illuminate\Support\Facades\Storage;
@endphp

<div
    class="flex flex-1 flex-wrap min-w-sm max-w-sm bg-[#FFEBFA] rounded-2xl shadow-lg overflow-hidden transform transition hover:scale-105 hover:shadow-2xl">
    {{-- Image --}}
    <div class="h-52 w-full">
        @if ($event->image && Storage::disk('public')->exists($event->image))
            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}"
                class="w-full h-full object-cover">
        @else
            <img src="{{ asset('images/placeholder.jpg') }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
        @endif
    </div>

    <div class="flex flex-1 flex-col p-6 space-y-4">
        {{-- Title --}}
        <h2 class="text-2xl font-bold text-[#3A4454] leading-tight">{{ $event->title }}</h2>

        {{-- Date & Time --}}
        <div class="flex items-center text-[#3A4454] text-sm">
            <span class="mr-2">@svg('heroicon-o-calendar-days', 'h-5 w-5')</span>
            <time datetime="{{ $event->date }}T{{ $event->time }}">
                {{ \Carbon\Carbon::parse($event->date)->format('d.m.Y') }} &bull;
                {{ \Carbon\Carbon::parse($event->time)->format('H:i') }}
            </time>
        </div>

        {{-- Type & Location --}}
        <div class="flex items-center text-[#3A4454] text-sm space-x-4">
            <div class="flex items-center">
                <span class="mr-2">@svg('heroicon-o-information-circle', 'h-5 w-5')</span>
                <span>{{ $event->type }}</span>
            </div>
            <div class="flex items-center">
                <span class="mr-2">@svg('heroicon-o-map-pin', 'h-5 w-5')</span>
                <span>{{ $event->location }}</span>
            </div>
        </div>

        {{-- Tickets & Organizer --}}
        <div class="flex items-center justify-between text-[#3A4454] text-sm">
            <div class="flex items-center">
                <span class="mr-2">@svg('heroicon-o-ticket', 'h-5 w-5')</span>
                <span>{{ $event->ticketSold }} / {{ $event->totalTickets }}</span>
            </div>
            <div class="flex items-center">
                <span class="mr-2">@svg('heroicon-o-user-group', 'h-5 w-5')</span>
                <span>{{ $event->organizer }}</span>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-1 pt-4">
            @if (auth()->check() && auth()->user()->role === 'admin')
                <div class="flex flex-1 space-x-4">
                    <form action="{{ route('events.destroy', $event) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this event?');" class="flex flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="flex-1 flex justify-center items-center bg-[#6B4E71] text-white font-bold py-2 rounded-xl shadow-md hover:bg-[#593b5c] transition">
                            Delete
                        </button>
                    </form>

                    <a href="{{ route('events.edit', $event) }}"
                        class="flex-1 block text-center bg-[#FFEBFA] border border-[#6B4E71] text-[#6B4E71] font-semibold py-2 rounded-xl hover:bg-[#6B4E71] hover:text-white transition">Edit</a>
                </div>
            @else
                <div class="flex flex-1 space-x-4">
                    <a href="{{ route('tickets.buy', $event) }}"
                        class="flex-1 text-center bg-[#6B4E71] text-white font-bold py-2 rounded-xl shadow-md hover:bg-[#593b5c] transition">Buy
                        Ticket</a>
                    <a href="{{ route('events.show', $event) }}"
                        class="flex-1 text-center border border-[#6B4E71] text-[#6B4E71] font-semibold py-2 rounded-xl hover:bg-[#6B4E71] hover:text-white transition">Details</a>
                </div>
            @endif
        </div>
    </div>
</div>
