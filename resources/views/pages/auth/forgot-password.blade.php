@extends('layouts.auth')
@section('title', 'Forgot Password')

@section('content')
<div class="flex flex-1 justify-center items-center bg-[#FFEBFA] p-6 rounded-lg shadow-md">
    <div class="w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-[#6B4E71]">Forgot your password?</h2>
        <p class="text-center text-sm text-[#3A4454] mb-6">
            Enter your email address and we'll send you a link to reset your password.
        </p>

        @if (session('status'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-sm text-center">
            {{ session('status') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm text-center">
            {{ $errors->first('email') }}
        </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <div class="flex flex-col">
                <label for="email" class="mb-1 font-medium text-[#3A4454]">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full px-4 py-2 border border-[#D7C1D3] bg-[#FFF7FD] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6B4E71]"
                    required>
            </div>

            <div class="text-center">
                <button type="submit"
                    class="bg-[#6B4E71] hover:bg-[#53687E] text-white font-semibold py-2 px-6 rounded-lg transition">
                    Send Reset Link
                </button>
            </div>
        </form>

        <div class="text-center text-sm text-[#3A4454] mt-6">
            <a href="{{ url('/login') }}" class="text-[#6B4E71] hover:underline">Back to login</a>
        </div>
    </div>
</div>
@endsection