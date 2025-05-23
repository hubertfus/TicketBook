@php
    use Illuminate\Support\Facades\Storage;
@endphp

<div class="rounded-lg shadow-md p-6 bg-[#FFEBFA] w-sm space-y-4">
    {{-- image --}}
    <div>
        @if ($event->image && Storage::disk('public')->exists($event->image))
            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}"
                class="w-full h-48 object-cover rounded-md">
        @else
            <img src="{{ asset('images/placeholder.jpg') }}" alt="{{ $event->title }}"
                class="w-full h-48 object-cover rounded-md">
        @endif
    </div>

    {{-- title --}}
    <div class="flex gap-1 flex-row">
        <h2 class="text-xl font-semibold text-[#3A4454]">{{ $event->title }}</h2>
    </div>

    {{-- start_time --}}
    <div class="flex gap-1 flex-row">
        <label class="block text-sm font-medium"> @svg('heroicon-o-calendar-days', 'h-5 w-5 flex-shrink-0')</label>
        <p class="text-sm text-[#3A4454]">{{ \Carbon\Carbon::parse($event->date)->format('d.m.Y') }}
            {{ \Carbon\Carbon::parse($event->time)->format('H:i') }}</p>
    </div>

    {{-- type --}}
    <div class="flex gap-1 flex-row">
        <label class="block text-sm font-medium">@svg('heroicon-o-information-circle', 'h-5 w-5 flex-shrink-0') </label>
        <p class="text-sm text-[#3A4454]">{{ $event->type }}</p>
    </div>

    {{-- location --}}
    <div class="flex gap-1 flex-row">
        <label class="block text-sm font-medium">@svg('heroicon-o-map-pin', 'h-5 w-5 flex-shrink-0') </label>
        <p class="text-sm text-[#3A4454]">{{ $event->location }}</p>
    </div>

    {{-- ticketSold --}}
    <div class="flex gap-1 flex-row">
        <label class="block text-sm font-medium">@svg('heroicon-o-ticket', 'h-5 w-5 flex-shrink-0')</label>
        <p class="text-sm text-[#3A4454]">{{ $event->ticketSold }} / {{ $event->totalTickets }} tickets sold</p>
    </div>

    {{-- organizer --}}
    <div class="flex gap-1 flex-row">
        <label class="block text-sm font-medium">@svg('heroicon-o-user-group', 'h-5 w-5 flex-shrink-0')</label>
        <p class="text-sm text-[#3A4454]">{{ $event->organizer }}</p>
    </div>

    {{-- admin controls / user actions --}}
    @if (auth()->check() && auth()->user()->role === 'admin')
        <div class="flex justify-between">
            <form action="{{ route('events.destroy', $event) }}" method="POST"
                onsubmit="return confirm('Are you sure you want to delete this event?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="hover:text-[#6B4E71] hover:font-bold hover:underline underline-offset-2">Delete</button>
            </form>

            <a href="{{ route('events.edit', $event) }}"
                class="hover:text-[#6B4E71] hover:font-bold hover:underline underline-offset-2">Edit</a>
        </div>
    @else
        <div class="flex items-center justify-between">
            <a href="" class="bg-[#6B4E71] text-white px-4 py-2 rounded-md text-sm hover:bg-[#593b5c]">
                Buy ticket
            </a>

            <a href="{{ route('events.show', $event) }}"
                class="text-[#6B4E71] text-sm hover:underline hover:font-semibold">
                View details
            </a>
        </div>
    @endif
</div>
