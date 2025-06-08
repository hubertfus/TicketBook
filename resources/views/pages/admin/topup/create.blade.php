@extends('layouts.admin')

@section('title', 'Generate Top-Up Code')

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
@endsection
