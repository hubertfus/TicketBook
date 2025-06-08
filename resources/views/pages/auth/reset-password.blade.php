@extends('layouts.auth')
@section('title', 'Reset Password')

@section('content')
    <div class="flex flex-1 justify-center items-center bg-[#FFEBFA] p-6 rounded-lg shadow-md">
        <div class="w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center text-[#6B4E71]">Reset your password</h2>

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm text-center">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="flex flex-col">
                    <label for="password" class="mb-1 font-medium text-[#3A4454]">New Password</label>
                    <input type="password" name="password"
                        class="w-full px-4 py-2 border border-[#D7C1D3] bg-[#FFF7FD] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6B4E71]"
                        required minlength="6">
                </div>

                <div class="flex flex-col">
                    <label for="password_confirmation" class="mb-1 font-medium text-[#3A4454]">Confirm Password</label>
                    <input type="password" name="password_confirmation"
                        class="w-full px-4 py-2 border border-[#D7C1D3] bg-[#FFF7FD] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6B4E71]"
                        required minlength="6">
                </div>

                <div class="text-center">
                    <button type="submit"
                        class="bg-[#6B4E71] hover:bg-[#53687E] text-white font-semibold py-2 px-6 rounded-lg transition">
                        Reset Password
                    </button>
                </div>
            </form>

            <div class="text-center text-sm text-[#3A4454] mt-6">
                <a href="{{ url('/login') }}" class="text-[#6B4E71] hover:underline">Back to login</a>
            </div>
        </div>
    </div>
@endsection
