@extends('layouts.user')
@section('title', 'My Transactions')

@php
    $filters = [
        [
            'name' => 'from_date',
            'type' => 'date',
            'label' => 'From Date',
            'icon' => 'heroicon-o-calendar',
        ],
        [
            'name' => 'to_date',
            'type' => 'date',
            'label' => 'To Date',
            'icon' => 'heroicon-o-calendar',
        ],
        [
            'name' => 'status',
            'type' => 'select',
            'label' => 'Order Status',
            'options' => $statuses->toArray(),
            'icon' => 'heroicon-o-tag',
        ],
    ];
@endphp

@section('content')
    {{-- Main Container --}}
    <div class="max-w-8xl mx-auto p-4 space-y-6">
        {{-- Centered Title --}}
        <div class="text-center py-6">
            <h1 class="text-2xl font-bold">My Transactions</h1>
        </div>

        {{-- Filters --}}
        <div class="searchbar-container">
            <x-searchbar :filters="$filters" :action="route('orders.index')" />
        </div>

        {{-- Orders Table --}}
        <div class="overflow-x-auto w-full">
            <table class="w-full bg-[#FFF7FD] shadow rounded-lg overflow-hidden">
                <thead class="bg-[#FFEBFA] text-gray-700 text-sm font-semibold">
                    <tr>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Total Price</th>
                        <th class="px-4 py-2 text-left">Created At</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-800 divide-y divide-gray-200">
                    @forelse ($orders as $order)
                        <tr>
                            <td class="px-4 py-2 capitalize">{{ $order->status }}</td>
                            <td class="px-4 py-2">PLN {{ number_format($order->total_price, 2, ',', ' ') }}</td>
                            <td class="px-4 py-2">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-2">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('orders.show', $order) }}"
                                        class="text-blue-600 hover:underline text-sm">Details</a>
                                    @if (!in_array($order->status, ['cancelled', 'refunded']))
                                        <form action="{{ route('orders.cancel', $order) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:underline text-sm">
                                                Cancel
                                            </button>
                                        </form>
                                    @elseif ($order->status !== 'refunded' && optional($order->orderItems->first()->ticket->event)->date < now())
                                        <form action="{{ route('orders.refund', $order) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to refund this order?');">
                                            @csrf
                                            <button type="submit" class="text-blue-600 hover:underline text-sm">
                                                Refund
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-500 text-sm">Unavailable</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-500">No transactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="w-full flex justify-center mt-6 py-4">
        {{ $orders->withQueryString()->links() }}
    </div>
@endsection
