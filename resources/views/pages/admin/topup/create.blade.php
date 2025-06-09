@extends('layouts.admin')

@section('title', 'Generate Top-Up Code')

@php
    $filters = [
        [
            'name' => 'code',
            'type' => 'text',
            'placeholder' => 'Code',
            'icon' => 'heroicon-o-key',
        ],
        [
            'name' => 'email',
            'type' => 'text',
            'placeholder' => 'User email',
            'icon' => 'heroicon-o-envelope',
        ],
        [
            'name' => 'is_used',
            'type' => 'select',
            'label' => 'Used?',
            'options' => ['1' => 'Yes', '0' => 'No'],
            'icon' => 'heroicon-o-check-circle',
        ],
    ];
@endphp

@section('content')
    <div class="max-w-lg mx-auto mt-10 bg-[#FFEBFA] p-6 rounded-xl shadow-lg">
        <h2 class="text-xl font-bold text-[#3A4454] mb-4">Generate Top-Up Code</h2>

        <form method="POST" action="{{ route('admin.topup.store') }}">
            @csrf

            <label class="block mb-2 text-[#3A4454]">Select User</label>
            <select name="user_id" required class="w-full mb-4 p-3 border border-[#D7C1D3] rounded text-[#3A4454]">
                <option value="">-- Select User --</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>

            <label class="block mb-2 text-[#3A4454]">Amount</label>
            <input type="number" name="value" step="0.01" min="1" required
                class="w-full mb-4 p-3 border border-[#D7C1D3] rounded text-[#3A4454]">

            <button type="submit" class="w-full bg-[#6B4E71] hover:bg-[#593b5c] text-white font-bold py-3 rounded-xl">
                Generate Code
            </button>
        </form>
    </div>

    {{-- Filters --}}
    <div class="max-w-6xl mx-auto mt-10">
        <x-searchbar :filters="$filters" :action="route('admin.topup.create')" />
    </div>

    @if ($codes->count())
        <div class="max-w-6xl mx-auto mt-6 bg-white shadow-lg rounded-xl overflow-hidden">
            <h3 class="text-lg font-bold text-[#3A4454] px-6 py-4 bg-[#FFEBFA]">All Generated Top-Up Codes</h3>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-gray-700">
                    <thead class="bg-[#FFF7FD] font-semibold text-[#3A4454]">
                        <tr>
                            <th class="px-6 py-3">Code</th>
                            <th class="px-6 py-3">Amount</th>
                            <th class="px-6 py-3">Used</th>
                            <th class="px-6 py-3">Assigned To</th>
                            <th class="px-6 py-3">Created At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#F3EAF6]">
                        @foreach ($codes as $code)
                            <tr>
                                <td class="px-6 py-3 font-mono font-bold">{{ $code->code }}</td>
                                <td class="px-6 py-3">PLN {{ number_format($code->value, 2, ',', ' ') }}</td>
                                <td class="px-6 py-3">
                                    @if ($code->is_used)
                                        <span class="text-green-600 font-semibold">Yes</span>
                                    @else
                                        <span class="text-gray-500">No</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3">
                                    {{ $code->user?->name ?? '-' }}
                                    <span class="text-xs text-gray-400">({{ $code->user?->email ?? '—' }})</span>
                                </td>
                                <td class="px-6 py-3">{{ $code->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="w-full flex justify-center mt-6 py-4">
                {{ $codes->withQueryString()->links() }}
            </div>
        </div>
    @endif
@endsection
