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

    {{-- date --}}
    <div class="flex gap-1 flex-row">
        <label class="block text-sm font-medium"> @svg('heroicon-o-calendar-days', 'h-5 w-5 flex-shrink-0')</label>
        <p class="text-sm text-[#3A4454]">{{ \Carbon\Carbon::parse($event->date)->format('d.m.Y') }}</p>
    </div>

    {{-- time --}}
    <div class="flex gap-1 flex-row">
        <label class="block text-sm font-medium">@svg('heroicon-o-clock', 'h-5 w-5 flex-shrink-0')</label>
        <p class="text-sm text-[#3A4454]">{{ $event->time }}</p>
    </div>

    {{-- venue --}}
    <div class="flex gap-1 flex-row">
        <label class="block text-sm font-medium">@svg('heroicon-o-map-pin', 'h-5 w-5 flex-shrink-0') </label>
        <p class="text-sm text-[#3A4454]">{{ $event->venue }}</p>
    </div>

    {{-- ticketSold --}}
    <div class="flex gap-1 flex-row">
        <label class="block text-sm font-medium">@svg('heroicon-o-ticket', 'h-5 w-5 flex-shrink-0')</label>
        <p class="text-sm text-[#3A4454]">{{ $event->ticketSold }} / {{ $event->totalTickets }} tickets sold</p>
    </div>

    {{-- status --}}
    <div class="flex gap-1 flex-row">
        <label class="block text-sm font-medium">@svg('heroicon-o-information-circle', 'h-5 w-5 flex-shrink-0')</label>
        <p class="text-sm text-[#3A4454]">{{ ucfirst($event->status) }}</p>
    </div>

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
</div>
