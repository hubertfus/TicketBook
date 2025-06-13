@extends('layouts.user')

@section('title', 'Your Top-Up Codes')

@section('content')
    <div class="w-full max-w-5xl mx-auto mt-20 mb-16 bg-[#FFEBFA] p-8 rounded-xl shadow-lg overflow-x-auto">
        <h2 class="text-2xl font-bold text-[#3A4454] mb-6">Your Top-Up Codes</h2>

        <div class="mb-6 p-4 bg-white border border-[#D7C1D3] rounded-xl flex items-center justify-between shadow-sm">
            <div class="text-[#3A4454]">
                <p class="text-sm">Your Current Balance:</p>
                <p class="text-2xl font-bold">${{ number_format(auth()->user()->balance, 2) }}</p>
            </div>
            <a href="{{ route('user.topup.form') }}"
                class="inline-block bg-[#6B4E71] hover:bg-[#593b5c] text-white font-semibold py-2 px-4 rounded-xl transition">
                Redeem a Code
            </a>
        </div>

        @if ($codes->isEmpty())
            <p class="text-[#3A4454]">You have no top-up codes assigned.</p>
        @else
            <table class="w-full table-auto text-left text-sm">
                <thead class="text-[#3A4454] border-b border-[#D7C1D3]">
                    <tr>
                        <th class="py-2 w-1/3">Code</th>
                        <th class="py-2 w-1/6">Value</th>
                        <th class="py-2 w-1/6">Used?</th>
                        <th class="py-2 w-1/3">Used At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($codes as $code)
                        <tr class="border-b border-[#D7C1D3]">
                            <td class="py-2 font-mono break-words">{{ $code->code }}</td>
                            <td class="py-2">${{ number_format($code->value, 2) }}</td>
                            <td class="py-2">
                                @if ($code->is_used)
                                    <span class="text-green-600 font-semibold">Yes</span>
                                @else
                                    <span class="text-red-600">No</span>
                                @endif
                            </td>
                            <td class="py-2">
                                @if (!$code->is_used)
                                    <form method="POST" action="{{ route('user.topup.redeemDirect', $code->code) }}">
                                        @csrf
                                        <button type="submit"
                                            class="bg-[#6B4E71] hover:bg-[#593b5c] text-white font-bold py-1 px-4 rounded-xl text-sm">
                                            Redeem
                                        </button>
                                    </form>
                                @else
                                    {{ $code->used_at ? $code->used_at->format('Y-m-d H:i') : '-' }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
