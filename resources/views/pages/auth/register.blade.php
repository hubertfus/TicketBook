@extends('layouts.auth')
@section('title', 'Register')

@section('content')
    <div class="flex flex-1 justify-center items-center bg-[#FFEBFA] p-6 rounded-lg shadow-md">
        <div class="w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center text-[#6B4E71]">Create an account</h2>

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ url('/register') }}" class="space-y-6">
                @csrf

                <div class="flex flex-col">
                    <label for="name" class="mb-1 font-medium text-[#3A4454]">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full px-4 py-2 border border-[#D7C1D3] bg-[#FFF7FD] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6B4E71]"
                           required>
                </div>

                <div class="flex flex-col">
                    <label for="email" class="mb-1 font-medium text-[#3A4454]">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full px-4 py-2 border border-[#D7C1D3] bg-[#FFF7FD] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6B4E71]"
                           required>
                </div>

                <div class="flex flex-col">
                    <label for="password" class="mb-1 font-medium text-[#3A4454]">Password</label>
                    <input type="password" name="password"
                           class="w-full px-4 py-2 border border-[#D7C1D3] bg-[#FFF7FD] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6B4E71]"
                           required minlength="6">
                </div>

                <div class="text-center">
                    <button type="submit"
                            class="bg-[#6B4E71] hover:bg-[#53687E] text-white font-semibold py-2 px-6 rounded-lg transition">
                        Register
                    </button>
                </div>
            </form>

            <div class="text-center text-sm text-[#3A4454] space-y-2 mt-6">
                <p>
                    Already have an account?
                    <a href="{{ url('/login') }}" class="text-[#6B4E71] hover:underline">Log in</a>
                </p>
            </div>
        </div>
    </div>
@endsection
