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
            <x-searchbar :filters="$filters" :action="route('user.orders.index')" />
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="overflow-x-auto w-full">
            <table class="w-full bg-[#FFF7FD] shadow rounded-lg overflow-hidden">
                <thead class="bg-[#FFEBFA] text-gray-700 text-sm font-semibold">
                    <tr>
                        <th class="px-4 py-2 text-left">Order ID</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Total Price</th>
                        <th class="px-4 py-2 text-left">Event Date</th>
                        <th class="px-4 py-2 text-left">Created At</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-800 divide-y divide-gray-200">
                    @forelse ($orders as $order)
                        @php
                            $event = optional($order->orderItems->first()->ticket->event);
                            $eventDate = $event->date ?? null;
                            $canCancel = in_array($order->status, ['paid', 'pending']) && $eventDate && $eventDate->isFuture();
                            $canRequestRefund = $order->status === 'cancelled' && !$order->refund && $eventDate && $eventDate->isPast();
                        @endphp
                        <tr>
                            <td class="px-4 py-2">#{{ $order->id }}</td>
                            <td class="px-4 py-2 capitalize">
                                <span class="px-2 py-1 rounded-full text-xs
                                    @if($order->status === 'paid') bg-green-100 text-green-800
                                    @elseif($order->status === 'cancelled') bg-gray-100 text-gray-800
                                    @elseif($order->status === 'refunded') bg-blue-100 text-blue-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-4 py-2">{{ number_format($order->total_price, 2, ',', ' ') }} PLN</td>
                            <td class="px-4 py-2">
                                @if($eventDate)
                                    {{ $eventDate->format('Y-m-d H:i') }}
                                    @if($eventDate->isPast())
                                        <span class="text-xs text-gray-500">(ended)</span>
                                    @else
                                        <span class="text-xs text-green-500">(upcoming)</span>
                                    @endif
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-2">
                                <div class="flex flex-col space-y-2">
                                    <a href="{{ route('user.orders.show', $order) }}"
                                        class="text-blue-600 hover:underline text-sm flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Details
                                    </a>

                                    @if($canCancel)
                                        <form action="{{ route('user.orders.cancel', $order) }}" method="POST" class="cancel-form">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="text-red-600 hover:underline text-sm flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Cancel
                                            </button>
                                        </form>
                                    @elseif($canRequestRefund)
                                        <button onclick="toggleRefundForm('{{ $order->id }}')"
                                            class="text-blue-600 hover:underline text-sm flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Request Refund
                                        </button>

                                        <form id="refund-form-{{ $order->id }}" class="mt-2 hidden"
                                            action="{{ route('user.orders.refund.request', $order) }}" method="POST">
                                            @csrf
                                            <textarea name="reason" placeholder="Why do you want a refund?"
                                                class="border rounded p-1 w-full text-sm" required rows="2"></textarea>
                                            <div class="flex space-x-2 mt-1">
                                                <button type="submit"
                                                    class="bg-blue-500 text-white px-3 py-1 rounded text-sm flex-1">
                                                    Submit
                                                </button>
                                                <button type="button" onclick="toggleRefundForm('{{ $order->id }}')"
                                                    class="bg-gray-500 text-white px-3 py-1 rounded text-sm">
                                                    Cancel
                                                </button>
                                            </div>
                                        </form>
                                    @elseif($order->refund)
                                        <span class="text-xs text-gray-500">
                                            Refund: {{ $order->refund->status }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">No transactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="w-full flex justify-center mt-6 py-4">
            {{ $orders->withQueryString()->links() }}
        </div>
    </div>

    <script>
        function toggleRefundForm(orderId) {
            const form = document.getElementById(`refund-form-${orderId}`);
            form.classList.toggle('hidden');
        }

        document.querySelectorAll('.cancel-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('Are you sure you want to cancel this order?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
