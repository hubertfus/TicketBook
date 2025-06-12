@extends('layouts.admin')

@section('content')
    @if ($errors->any())
        <div class="bg-red-100 text-red-600 p-4 mb-4 rounded-2xl shadow-inner">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="min-h-screen bg-[#FFF7FD] py-10 px-4">
        <div class="max-w-3xl mx-auto bg-[#FFEBFA] rounded-2xl shadow-lg p-8">
            <h2 class="text-3xl font-extrabold text-[#3A4454] flex items-center gap-2 mb-6">
                @svg('heroicon-o-pencil-square', 'w-6 h-6 text-[#6B4E71]') Edit Ticket
            </h2>

            <form action="{{ route('tickets.update', $ticket) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-[#3A4454] mb-2">
                        @svg('heroicon-o-calendar-days', 'w-4 h-4 inline mr-1 text-[#6B4E71]') Event </label>
                    <p class="p-3 bg-white rounded-xl shadow-inner">{{ $ticket->event->title }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#3A4454] mb-2">
                        @svg('heroicon-o-tag', 'w-4 h-4 inline mr-1 text-[#6B4E71]') Category
                    </label>
                    <input type="text" name="category"
                        value="{{ old('category', $ticket->category) }}"class="w-full px-4 py-3 bg-white rounded-xl shadow-inner focus:outline-none focus:ring-2 focus:ring-[#6B4E71]" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#3A4454] mb-2">
                        @svg('heroicon-o-currency-dollar', 'w-4 h-4 inline mr-1 text-[#6B4E71]') Price
                    </label>
                    <input type="number" name="price" value="{{ $ticket->price }}" min="5" step="0.01" required
                        class="w-full px-4 py-3 bg-white rounded-xl shadow-inner focus:outline-none focus:ring-2 focus:ring-[#6B4E71]" />
                </div>

                <div class="pt-6 border-t border-[#6B4E71]/20 flex justify-end gap-4">
                    <a href="{{ route('tickets.byEvent', $ticket->event_id) }}"
                        class="px-6 py-3 rounded-xl bg-transparent border border-[#6B4E71] text-[#6B4E71] hover:bg-[#6B4E71] hover:text-white transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-8 py-3 rounded-xl bg-gradient-to-r from-[#6B4E71] to-[#8D6595] text-white font-semibold shadow-md hover:opacity-90 transition">
                        Update Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
