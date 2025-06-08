@extends('layouts.user')

@section('title', 'Buy Tickets for ' . $event->title)

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
    <div class="min-h-screen bg-[#FFF7FD] py-10 px-4">
        <div class="max-w-4xl mx-auto bg-[#FFEBFA] rounded-2xl shadow-lg overflow-hidden">
            <div class="relative h-64 w-full overflow-hidden">
                @if ($event->image && Storage::disk('public')->exists($event->image))
                    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}"
                        class="object-cover w-full h-full">
                @else
                    <img src="{{ asset('images/placeholder.jpg') }}" alt="{{ $event->title }}"
                        class="object-cover w-full h-full">
                @endif
                <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-black/60 to-transparent p-4">
                    <h1 class="text-3xl font-bold text-white">{{ $event->title }}</h1>
                </div>
            </div>
            <form method="POST" action="{{ route('tickets.store', $event) }}" class="p-8 space-y-6">
                @if ($errors->has('quantities'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ $errors->first('quantities') }}
                    </div>
                @endif

                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-[#3A4454] text-sm">
                    <div class="space-y-2">

                        <div class="flex items-center">
                            <span class="mr-2">@svg('heroicon-o-map-pin', 'h-5 w-5 text-[#6B4E71]')</span>
                            <span>{{ $event->location }}</span>
                        </div>

                        <div class="flex items-center">
                            <span class="mr-2">@svg('heroicon-o-calendar-days', 'h-5 w-5 text-[#6B4E71]')</span>
                            <span>{{ \Carbon\Carbon::parse($event->date)->format('d.m.Y') }}</span>
                        </div>

                        <div class="flex items-center">
                            <span class="mr-2">@svg('heroicon-o-clock', 'h-5 w-5 text-[#6B4E71]')</span>
                            <span>{{ \Carbon\Carbon::parse($event->time)->format('H:i') }}</span>
                        </div>
                    </div>

                    <div class="space-y-2 sm:text-right">
                        <div class="flex items-center justify-start sm:justify-end">
                            <span class="mr-2">@svg('heroicon-o-information-circle', 'h-5 w-5 text-[#6B4E71]')</span>
                            <span>{{ ucfirst($event->type) }}</span>
                        </div>
                        <div class="flex items-center justify-start sm:justify-end">
                            <span class="mr-2">@svg('heroicon-o-user-group', 'h-5 w-5 text-[#6B4E71]')</span>
                            <span>{{ $event->organizer }}</span>
                        </div>
                        <div class="flex items-center justify-start sm:justify-end">
                            <span class="mr-2">@svg('heroicon-o-ticket', 'h-5 w-5 text-[#6B4E71]')</span>
                            <span>{{ $event->ticketSold }} / {{ $event->totalTickets }} tickets sold</span>
                        </div>
                    </div>
                </div>

                <h2 class="text-xl font-semibold text-[#3A4454] pt-6">Select Tickets</h2>

                @foreach ($event->tickets as $ticket)
                    <div
                        class="flex flex-wrap sm:flex-nowrap items-center justify-between gap-4 py-3 border-b border-[#D7C1D3]">
                        <div class="flex items-center gap-3 min-w-[120px]">
                            @svg('heroicon-o-ticket', 'h-5 w-5 text-[#6B4E71]')
                            <span class="font-medium text-[#3A4454] capitalize">{{ $ticket->category }}</span>
                            <span class="text-[#3A4454] font-medium">${{ number_format($ticket->price, 2) }}</span>
                        </div>
                        <input type="number" name="quantities[{{ $ticket->id }}]" min="0"
                            max="{{ $ticket->quantity }}" data-price="{{ $ticket->price }}"
                            class="ticket-input w-20 px-3 py-2 border border-[#D7C1D3] rounded-lg bg-white text-[#3A4454] focus:ring-2 focus:ring-[#6B4E71]"
                            placeholder="0">
                    </div>
                @endforeach
                <div class="flex justify-end text-[#3A4454] font-semibold text-lg pt-4">
                    <span>Total: </span>
                    <span id="total-price" class="ml-2">$0.00</span>
                </div>

                <div class="flex flex-col items-center gap-4 pt-6">
                    <button type="submit"
                        class="bg-[#6B4E71] hover:bg-[#593b5c] text-white font-semibold py-3 px-6 rounded-xl transition w-full max-w-sm">
                        Pay
                    </button>
                    @guest
                        <p class="text-sm text-[#3A4454]">
                            You must be <a href="{{ route('login') }}" class="underline text-[#6B4E71]">logged in</a> to
                            complete your purchase.
                        </p>
                    @endguest
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const inputs = document.querySelectorAll('.ticket-input');
                const totalDisplay = document.getElementById('total-price');

                function updateTotal() {
                    let total = 0;
                    inputs.forEach(input => {
                        const quantity = parseInt(input.value) || 0;
                        const price = parseFloat(input.dataset.price);
                        total += quantity * price;
                    });
                    totalDisplay.textContent = '$' + total.toFixed(2);
                }

                inputs.forEach(input => {
                    input.addEventListener('input', updateTotal);
                });

                updateTotal();
            });
        </script>
    @endpush

@endsection
