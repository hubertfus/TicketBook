@extends('layouts.guest')

@section('content')
    <h2 class="text-2xl font-bold mb-6 text-center text-[#6B4E71]">Logowanie</h2>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ $errors->first('email') }}
        </div>
    @endif

    <form method="POST" action="{{ url('/login') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="block mb-1 font-medium text-[#3A4454]">Email</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="w-full px-4 py-2 border border-[#D7C1D3] bg-[#FFF7FD] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6B4E71]"
                   required>
        </div>

        <div class="mb-6">
            <label for="password" class="block mb-1 font-medium text-[#3A4454]">Hasło</label>
            <input type="password" name="password"
                   class="w-full px-4 py-2 border border-[#D7C1D3] bg-[#FFF7FD] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6B4E71]"
                   required minlength="6">
        </div>

        <div class="text-center mb-4">
            <button type="submit"
                    class="bg-[#6B4E71] hover:bg-[#53687E] text-white font-semibold py-2 px-6 rounded-lg transition">
                Zaloguj się
            </button>
        </div>
    </form>

    <div class="text-center text-sm text-[#3A4454] space-y-2">
        <p>
            Nie masz konta?
            <a href="{{ url('/register') }}" class="text-[#6B4E71] hover:underline">Zarejestruj się</a>
        </p>
        <p>
            <a href="{{ url('/forgot-password') }}" class="text-[#6B4E71] hover:underline">Zapomniałeś hasła?</a>
        </p>
    </div>
@endsection
