<div class="rounded-lg shadow-md p-6 bg-[#FFEBFA] w-sm space-y-4">
    {{-- image --}}
    @if ($event->image)
        <div>
            <img src="{{ $event->image }}" alt="{{ $event->title }}" class="w-full h-48 object-cover rounded-md">
        </div>
    @endif

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

    {{-- admin controls --}}
    @if (!auth()->check() || auth()->user()->role === 'admin')
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
    @endif
</div>
