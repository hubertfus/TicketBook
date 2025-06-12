@extends('layouts.admin')
@section('title', 'Tickets for ' . $event->title)

@php
    $filters = [
        [
            'name' => 'category',
            'type' => 'select',
            'label' => 'Category',
            'options' => $event->tickets()->pluck('category')->unique()->values()->toArray(),
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
        <a href="{{ route('tickets.create', $event) }}"
            class="bg-[#6B4E71] text-white px-4 py-2 rounded hover:bg-[#48354D]">Add
            Category</a>
    </div>
    @if ($errors->any())
        <div class="bg-red-100 text-red-600 p-4 mb-4 rounded shadow-inner">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-4 mb-4 rounded shadow-inner">
            {{ session('success') }}
        </div>
    @endif

    <div class="w-full relative z-20 max-w-7xl mx-auto p-4 sm:p-5">
        <x-searchbar :filters="$filters" :action="route('tickets.byEvent', $event->id)" />
    </div>

    <div class="max-w-7xl mx-auto overflow-x-auto p-4">
        <table class="min-w-full bg-[#FFF7FD] shadow rounded-lg overflow-hidden">
            <thead class="bg-[#FFEBFA] text-gray-700 text-sm font-semibold">
                <tr>
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Category</th>
                    <th class="px-4 py-2 text-left">Price</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-800 divide-y divide-gray-200">
                @forelse ($tickets as $ticket)
                    <tr>
                        <td class="px-4 py-2">#{{ $ticket->id }}</td>
                        <td class="px-4 py-2 capitalize">{{ $ticket->category }}</td>
                        <td class="px-4 py-2">${{ number_format($ticket->price, 2, ',', ' ') }}</td>
                        <td class="px-4 py-2">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('tickets.edit', $ticket) }}"
                                    class="text-blue-600 hover:underline text-sm">Edit</a>
                                <form action="{{ route('tickets.destroy', $ticket) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this ticket?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline text-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-gray-500">No tickets found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="w-full flex justify-center mt-6 py-6">
        <div class="max-w-sm">
            {{ $tickets->withQueryString()->links() }}
        </div>
    </div>
@endsection
