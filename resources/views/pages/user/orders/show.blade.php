@extends('layouts.user')
@section('title', 'Order Details')

@section('content')
    {{-- Main Container --}}
    <div class="max-w-8xl mx-auto p-4 space-y-6">
        {{-- Centered Title with Back Link --}}
        <div class="text-center py-6 relative">
            <h1 class="text-2xl font-bold">Order Details</h1>
            <a href="{{ route('user.orders.index') }}"
                class="absolute right-0 top-1/2 transform -translate-y-1/2 text-[#6B4E71] hover:underline">
                ← Back to My Transactions
            </a>
        </div>

        {{-- Order Summary --}}
        <div class="bg-[#FFF7FD] shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Order Summary</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Order ID</label>
                    <p class="mt-1 text-sm text-gray-900">#{{ $order->id }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Status</label>
                    <p class="mt-1">
                        <span
                            class="inline-flex px-2 py-1 text-xs font-medium rounded-full capitalize
                            @if ($order->status === 'paid') bg-green-100 text-green-800
                            @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                            @elseif($order->status === 'refunded') bg-gray-100 text-gray-800
                            @else bg-blue-100 text-blue-800 @endif">
                            {{ $order->status }}
                        </span>
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Total Price</label>
                    <p class="mt-1 text-sm font-bold text-gray-900">PLN
                        {{ number_format($order->total_price, 2, ',', ' ') }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Order Date</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $order->created_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>
        </div>

        {{-- Order Items Table --}}
        <div class="overflow-x-auto">
            <table class="w-full bg-[#FFF7FD] shadow rounded-lg overflow-hidden">
                <thead class="bg-[#FFEBFA] text-gray-700 text-sm font-semibold">
                    <tr>
                        <th class="px-4 py-2 text-left">Event</th>
                        <th class="px-4 py-2 text-left">Ticket Category</th>
                        <th class="px-4 py-2 text-center">Quantity</th>
                        <th class="px-4 py-2 text-right">Unit Price</th>
                        <th class="px-4 py-2 text-right">Total Price</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-800 divide-y divide-gray-200">
                    @forelse($order->orderItems as $item)
                        <tr>
                            <td class="px-4 py-2">
                                <div>
                                    <p class="font-medium">{{ $item->ticket->event->title }}</p>
                                    <p class="text-xs text-gray-500">{{ $item->ticket->event->date->format('Y-m-d H:i') }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $item->ticket->event->location }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-2">
                                <span
                                    class="inline-flex px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                    {{ $item->ticket->category }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-center font-medium">{{ $item->quantity }}</td>
                            <td class="px-4 py-2 text-right">PLN {{ number_format($item->unit_price, 2, ',', ' ') }}</td>
                            <td class="px-4 py-2 text-right font-medium">PLN
                                {{ number_format($item->total_price, 2, ',', ' ') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-500">No items found in this order.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-[#FFEBFA]">
                    <tr>
                        <td colspan="4" class="px-4 py-2 text-right font-semibold">Total Order Amount:</td>
                        <td class="px-4 py-2 text-right font-bold text-lg">PLN
                            {{ number_format($order->total_price, 2, ',', ' ') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="text-right mt-6">
            <a href="{{ route('user.orders.confirmation', $order) }}"
                class="inline-block px-4 py-2 bg-[#6B4E71] text-white rounded hover:bg-[#53687E] transition">
                Download Confirmation (PDF)
            </a>
        </div>

    </div>
@endsection
