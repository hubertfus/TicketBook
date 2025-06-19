@extends('layouts.admin')

@section('title', 'Refund Requests')

@php
    $filters = [
        [
            'name' => 'user',
            'type' => 'text',
            'placeholder' => 'User name',
            'icon' => 'heroicon-o-user',
        ],
        [
            'name' => 'status',
            'type' => 'select',
            'label' => 'Refund status',
            'options' => ['requested' => 'Requested', 'approved' => 'Approved', 'rejected' => 'Rejected'],
            'icon' => 'heroicon-o-tag',
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
    ];
@endphp

@section('content')
    <div class="px-6 py-6 mt-4">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Refund Requests</h1>
        </div>
    </div>

    {{-- Filters --}}
    <div class="max-w-7xl mx-auto p-4">
        <x-searchbar :filters="$filters" :action="route('admin.refunds.index')" />
    </div>

    {{-- Refunds Table --}}
    <div class="max-w-7xl mx-auto overflow-x-auto p-4">
        <table class="min-w-full bg-[#FFF7FD] shadow rounded-lg overflow-hidden">
            <thead class="bg-[#FFEBFA] text-gray-700 text-sm font-semibold">
                <tr>
                    <th class="px-4 py-2 text-left">Order ID</th>
                    <th class="px-4 py-2 text-left">User</th>
                    <th class="px-4 py-2 text-left">Amount</th>
                    <th class="px-4 py-2 text-left">Reason</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-800 divide-y divide-gray-200">
                @forelse ($refunds as $refund)
                    <tr>
                        <td class="px-4 py-2">#{{ $refund->order->id }}</td>
                        <td class="px-4 py-2">{{ $refund->order->user->name ?? '–' }} ({{ $refund->order->user->email ?? '–' }})</td>
                        <td class="px-4 py-2">{{ number_format($refund->order->total_price, 2, ',', ' ') }} PLN</td>
                        <td class="px-4 py-2">{{ $refund->reason }}</td>
                        <td class="px-4 py-2 capitalize">{{ $refund->status }}</td>
                        <td class="px-4 py-2 space-x-2">
                            @if ($refund->status === 'requested')
                                <form action="{{ route('admin.refunds.approve', $refund) }}" method="POST" class="inline">
                                    @csrf
                                    <button
                                        class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600">Approve</button>
                                </form>
                                <form action="{{ route('admin.refunds.reject', $refund) }}" method="POST" class="inline">
                                    @csrf
                                    <button
                                        class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">Reject</button>
                                </form>
                            @else
                                <span class="text-gray-500 text-sm">No actions</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500">No refund requests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection
