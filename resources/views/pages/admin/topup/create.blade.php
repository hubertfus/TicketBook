@extends('layouts.admin')
@section('title', 'Top-Up Codes')

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
    <div class="flex justify-between items-center bg-[#FFEBFA] p-4 rounded-t-xl shadow">
        <h1 class="text-2xl font-bold text-[#3A4454]">Generate Top-Up Code</h1>
    </div>

    <div class="max-w-4xl mx-auto mt-10 p-6 bg-[#FFEBFA] shadow-md rounded-xl">
        <form method="POST" action="{{ route('admin.topup.store') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-[#3A4454] mb-2">Select User</label>
                <select name="user_id" required
                    class="w-full px-4 py-3 bg-white rounded-xl shadow-inner border border-[#D7C1D3]">
                    <option value="">-- Select User --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-[#3A4454] mb-2">Amount</label>
                <input type="number" name="value" step="0.01" min="1" required
                    class="w-full px-4 py-3 bg-white rounded-xl shadow-inner border border-[#D7C1D3]" />
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit"
                    class="px-8 py-3 rounded-xl bg-gradient-to-r from-[#6B4E71] to-[#8D6595] text-white font-semibold shadow-md hover:opacity-90 transition">
                    Generate Code
                </button>
            </div>
        </form>
    </div>

    <div class="w-full relative z-20 max-w-7xl mx-auto mt-10 p-4 sm:p-5">
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
                                <td class="px-6 py-3">{{ number_format($code->value, 2, ',', ' ') }} PLN</td>
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

        <div class="w-full flex justify-center mt-6 py-6">
            <div class="max-w-sm">
                {{ $codes->withQueryString()->links() }}
            </div>
        </div>
    @endif
@endsection
