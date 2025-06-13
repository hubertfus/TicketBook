@extends('layouts.user')

@section('title', 'Top Up Your Balance')

@section('content')
    <div class="max-w-md mx-auto mt-20 mb-16 bg-[#FFEBFA] p-6 rounded-xl shadow-lg">
        <h2 class="text-xl font-bold text-[#3A4454] mb-4">Redeem a Top-Up Code</h2>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-200 text-green-900 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('user.topup.redeem') }}">
            @csrf

            <label class="block text-sm font-medium text-[#3A4454] mb-2">Enter Code</label>
            <input type="text" name="code" maxlength="10" placeholder="ABC123XYZ9" value="{{ old('code') }}"
                class="w-full px-4 py-3 bg-white border border-[#D7C1D3] rounded-xl shadow-inner text-[#3A4454]" />

            @error('code')
                <p class="text-red-600 text-sm mb-2">{{ $message }}</p>
            @enderror

            <button type="submit" class="w-full bg-[#6B4E71] hover:bg-[#593b5c] text-white font-bold py-3 rounded-xl mt-3">
                Redeem
            </button>
        </form>
    </div>
@endsection
