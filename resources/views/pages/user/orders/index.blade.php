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
    <div class="max-w-8xl mx-auto p-4 space-y-6">
        <div class="text-center py-6">
            <h1 class="text-2xl font-bold">My Transactions</h1>
        </div>

        <div class="searchbar-container">
            <x-searchbar :filters="$filters" :action="route('orders.index')" />
        </div>

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
                                <div class="flex flex-col space-y-1">
                                    <a href="{{ route('orders.show', $order) }}"
                                        class="text-blue-600 hover:underline text-sm">Details</a>

                                    @php
                                        $eventDate = optional($order->orderItems->first()->ticket->event)->date;
                                    @endphp

                                    @if (in_array($order->status, ['cancelled', 'refunded']))
                                        {{-- Zamówienie anulowane lub zwrócone --}}
                                        @if (!$order->refund)
                                            <span class="text-gray-500 text-sm">Unavailable</span>
                                        @else
                                            <span class="text-gray-500 text-sm">Refund requested
                                                ({{ $order->refund->status }})
                                            </span>
                                        @endif
                                    @elseif ($eventDate && $eventDate->isPast())
                                        {{-- Event się odbył, można zrobić refund --}}
                                        @if (!$order->refund)
                                            <button
                                                onclick="document.getElementById('refund-form-{{ $order->id }}').classList.toggle('hidden')"
                                                class="text-blue-600 hover:underline text-sm">
                                                Request Refund
                                            </button>

                                            <form id="refund-form-{{ $order->id }}" class="mt-2 hidden"
                                                action="{{ route('orders.refund.request', $order) }}" method="POST">
                                                @csrf
                                                <textarea name="reason" placeholder="Why do you want a refund?" class="border rounded p-1 w-full text-sm" required></textarea>
                                                <button type="submit"
                                                    class="mt-1 bg-blue-500 text-white px-3 py-1 rounded text-sm">
                                                    Send Request
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-500 text-sm">Refund requested
                                                ({{ $order->refund->status }})</span>
                                        @endif
                                    @else
                                        {{-- Można anulować --}}
                                        <form action="{{ route('orders.cancel', $order) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:underline text-sm">
                                                Cancel
                                            </button>
                                        </form>
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

    <div class="w-full flex justify-center mt-6 py-4">
        {{ $orders->withQueryString()->links() }}
    </div>
@endsection
