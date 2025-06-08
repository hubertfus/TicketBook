@extends('layouts.user')

@section('title', 'Confirm Your Payment')

@section('content')
    <div class="min-h-screen bg-[#FFF7FD] py-16 px-4">
        <div class="max-w-2xl mx-auto bg-[#FFEBFA] rounded-3xl shadow-xl p-10 space-y-8">

            <div class="flex justify-end">
                <div class="flex items-center gap-2 bg-white/50 px-5 py-2 rounded-xl shadow-inner">
                    @svg('heroicon-o-banknotes', 'h-5 w-5 text-[#6B4E71]')
                    <span class="font-medium text-[#3A4454]">
                        ${{ number_format(auth()->user()->balance, 2) }}
                    </span>
                </div>
            </div>

            <h2 class="text-2xl font-bold text-[#3A4454] text-center">Payment Summary</h2>

            <div class="space-y-4 text-[#3A4454] text-base">
                <div class="flex items-center gap-2">
                    <span class="font-semibold">Event:</span>
                    <span>{{ $event->title }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="font-semibold">Date:</span>
                    <span>{{ \Carbon\Carbon::parse($event->date)->format('d.m.Y') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="font-semibold">Total Price:</span>
                    <span>${{ number_format($total, 2) }}</span>
                </div>

                <div>
                    <h3 class="font-semibold mt-4 mb-2">Tickets:</h3>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($tickets as $ticket)
                            <li>
                                {{ $ticket['quantity'] }} × {{ ucfirst($ticket['category']) }} —
                                ${{ number_format($ticket['price'], 2) }} each
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            @if ($errors->has('balance'))
                <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ $errors->first('balance') }}
                </div>
            @endif

            <div class="flex gap-6">
                <form method="POST" action="{{ route('payment.pay', $event) }}" class="flex-1">
                    @csrf
                    <button type="submit"
                        class="w-full py-3 rounded-xl bg-[#6B4E71] hover:bg-[#593b5c] text-white font-semibold transition">
                        Confirm Payment
                    </button>
                </form>
                <a href="{{ route('events.show', $event) }}"
                    class="flex-1 block text-center py-3 rounded-xl bg-[#E0E0E0] hover:bg-[#CCCCCC] text-[#3A4454] font-semibold transition">
                    Cancel
                </a>
            </div>

        </div>
    </div>
@endsection
