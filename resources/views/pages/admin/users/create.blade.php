{{-- resources/views/pages/admin/users/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Create User')

@section('content')
    <div class="min-h-screen bg-[#FFF7FD] py-10 px-4">
        <div class="max-w-xl mx-auto bg-[#FFEBFA] rounded-2xl shadow-lg p-8">
            <h2 class="text-3xl font-extrabold text-[#3A4454] flex items-center gap-2 mb-6">
                @svg('heroicon-o-user-plus', 'w-6 h-6 text-[#6B4E71]') Create New User
            </h2>

            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-100 text-red-600 p-4 mb-4 rounded-2xl shadow-inner">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Name --}}
                <div>
                    <label class="block text-sm font-medium text-[#3A4454] mb-2">
                        @svg('heroicon-o-user', 'w-4 h-4 inline mr-1 text-[#6B4E71]') Name
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-3 bg-white rounded-xl shadow-inner focus:outline-none focus:ring-2 focus:ring-[#6B4E71]">
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-[#3A4454] mb-2">
                        @svg('heroicon-o-envelope', 'w-4 h-4 inline mr-1 text-[#6B4E71]') Email
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 bg-white rounded-xl shadow-inner focus:outline-none focus:ring-2 focus:ring-[#6B4E71]">
                </div>

                {{-- Role --}}
                <div>
                    <label class="block text-sm font-medium text-[#3A4454] mb-2">
                        @svg('heroicon-o-shield-check', 'w-4 h-4 inline mr-1 text-[#6B4E71]') Role
                    </label>
                    <select name="role" id="role" required
                        class="w-full px-4 py-3 bg-white rounded-xl shadow-inner focus:outline-none focus:ring-2 focus:ring-[#6B4E71]">
                        <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                {{-- Balance --}}
                <div>
                    <label class="block text-sm font-medium text-[#3A4454] mb-2">
                        @svg('heroicon-o-currency-dollar', 'w-4 h-4 inline mr-1 text-[#6B4E71]') Balance
                    </label>
                    <input type="number" step="0.01" name="balance" id="balance" value="{{ old('balance') }}"
                        class="w-full px-4 py-3 bg-white rounded-xl shadow-inner focus:outline-none focus:ring-2 focus:ring-[#6B4E71]">
                    <p class="text-xs text-[#6B4E71]/70 mt-1">Leave empty to set to 0</p>
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-medium text-[#3A4454] mb-2">
                        @svg('heroicon-o-lock-closed', 'w-4 h-4 inline mr-1 text-[#6B4E71]') Password
                    </label>
                    <input type="password" name="password" id="password" required autocomplete="new-password"
                        class="w-full px-4 py-3 bg-white rounded-xl shadow-inner focus:outline-none focus:ring-2 focus:ring-[#6B4E71]">
                </div>

                {{-- Actions --}}
                <div class="pt-6 border-t border-[#6B4E71]/20 flex justify-end gap-4">
                    <a href="{{ route('admin.users.index') }}"
                        class="px-6 py-3 rounded-xl bg-transparent border border-[#6B4E71] text-[#6B4E71] hover:bg-[#6B4E71] hover:text-white transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-8 py-3 rounded-xl bg-gradient-to-r from-[#6B4E71] to-[#8D6595] text-white font-semibold shadow-md hover:opacity-90 transition">
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
