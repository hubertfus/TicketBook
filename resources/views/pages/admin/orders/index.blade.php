@extends('layouts.admin')
@section('title', 'Transactions')

@php
    $filters = [
        [
            'name' => 'user',
            'type' => 'text',
            'placeholder' => 'User name',
            'icon' => 'heroicon-o-user',
        ],
        [
            'name' => 'from_date',
            'type' => 'date',
            'label' => 'From date',
            'icon' => 'heroicon-o-calendar',
        ],
        [
            'name' => 'to_date',
            'type' => 'date',
            'label' => 'To date',
            'icon' => 'heroicon-o-calendar',
        ],
        [
            'name' => 'status',
            'type' => 'select',
            'label' => 'Order status',
            'options' => $statuses->toArray(),
            'icon' => 'heroicon-o-tag',
        ],
    ];
@endphp

@section('content')
    <div class="px-6 py-6 mt-4">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Transactions</h1>
        <a href="{{ route('orders.create') }}" class="bg-[#6B4E71] text-white px-4 py-2 rounded hover:bg-[#48354D]">
            Add Order
        </a>
    </div>
</div>

    {{-- Filters --}}
    <div class="max-w-7xl mx-auto p-4">
        <x-searchbar :filters="$filters" :action="route('orders.index')" />
    </div>

    {{-- Orders Table --}}
    <div class="overflow-x-auto p-4">
        <table class="min-w-full bg-[#FFF7FD] shadow rounded-lg overflow-hidden">
            <thead class="bg-[#FFEBFA] text-gray-700 text-sm font-semibold">
                <tr>
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">User</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Total Price</th>
                    <th class="px-4 py-2 text-left">Created At</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-800 divide-y divide-gray-200">
                @forelse ($orders as $order)
                    <tr>
                        <td class="px-4 py-2">#{{ $order->id }}</td>
                        <td class="px-4 py-2">{{ $order->user->name ?? '–' }}</td>
                        <td class="px-4 py-2 capitalize">{{ $order->status }}</td>
                        <td class="px-4 py-2">PLN {{ number_format($order->total_price, 2, ',', ' ') }}</td>
                        <td class="px-4 py-2">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-2">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('orders.edit', $order) }}" class="text-blue-600 hover:underline text-sm">Edit</a>
                                <form action="{{ route('orders.destroy', $order) }}" method="POST" onsubmit="return confirm('Delete this order?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline text-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="w-full flex justify-center mt-6 py-4">
        {{ $orders->withQueryString()->links() }}
    </div>
@endsection
