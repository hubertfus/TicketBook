@extends('layouts.admin')
@section('title', 'Tickets for ' . $event->title)

@php
    $filters = [
        [
            'name' => 'category',
            'type' => 'select',
            'label' => 'Category',
            'options' => ['standard', 'vip', 'student'],
            'icon' => 'heroicon-o-tag',
        ],
        [
            'name' => 'price',
            'type' => 'text',
            'placeholder' => 'Max price',
            'icon' => 'heroicon-o-currency-dollar',
        ],
    ];
@endphp

@section('content')
    <div class="flex justify-between items-center bg-[#FFEBFA] p-4">
        <h1 class="text-2xl font-bold">Tickets for "{{ $event->title }}"</h1>
    </div>

    <div class="overflow-x-auto p-4 sm:p-5">
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="w-full relative z-20 max-w-7xl mx-auto p-4 sm:p-5">
            <x-searchbar :filters="$filters" :action="route('tickets.byEvent', $event->id)" />
        </div>


        <table class="min-w-full bg-white rounded-lg shadow-md overflow-hidden">
            <thead class="bg-[#D7C1D3] text-[#3A4454]">
                <tr>
                    <th class="py-2 px-4 text-left">ID</th>
                    <th class="py-2 px-4 text-left">Category</th>
                    <th class="py-2 px-4 text-left">Price</th>
                    <th class="py-2 px-4 text-left">Quantity</th>
                    <th class="py-2 px-4 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tickets as $ticket)
                    <tr class="border-t">
                        <td class="py-2 px-4">{{ $ticket->id }}</td>
                        <td class="py-2 px-4 capitalize">{{ $ticket->category }}</td>
                        <td class="py-2 px-4">${{ number_format($ticket->price, 2) }}</td>
                        <td class="py-2 px-4">{{ $ticket->quantity }}</td>
                        <td class="py-2 px-4 flex gap-2">
                            <a href="{{ route('tickets.edit', $ticket) }}"
                                class="bg-[#FFEBFA] hover:bg-[#6B4E71]  border-[#6B4E71] text-[#6B4E71] hover:text-white  px-4 py-2 rounded shadow-md transition">Edit</a>
                            <form action="{{ route('tickets.destroy', $ticket) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this ticket?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-[#6B4E71] hover:bg-[#593b5c] text-white px-4 py-2 rounded shadow-md transition">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-4 px-4 text-center text-gray-500">No tickets found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-6">
            {{ $tickets->withQueryString()->links() }}
        </div>
    </div>
@endsection
