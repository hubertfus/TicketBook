@extends('layouts.user')

@section('title', 'My Account')

@section('content')
    <div class="min-h-screen bg-[#FFF7FD] py-10 px-4">
        <div class="max-w-3xl mx-auto bg-[#FFEBFA] rounded-2xl shadow-lg p-8">
            <h2 class="text-3xl font-extrabold text-[#3A4454] flex items-center gap-2 mb-6">
                @svg('heroicon-o-user-circle', 'w-6 h-6 text-[#6B4E71]') My Account
            </h2>

            @if ($errors->any())
                <div class="bg-red-100 text-red-600 p-4 mb-6 rounded-2xl shadow-inner">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-100 text-green-600 p-4 mb-6 rounded-2xl shadow-inner">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('user.profile.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Name --}}
                <div>
                    <label class="block text-sm font-medium text-[#3A4454] mb-2">
                        @svg('heroicon-o-user', 'w-4 h-4 inline mr-1 text-[#6B4E71]') Full Name
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-4 py-3 bg-white rounded-xl shadow-inner focus:outline-none focus:ring-2 focus:ring-[#6B4E71]" />
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-[#3A4454] mb-2">
                        @svg('heroicon-o-envelope', 'w-4 h-4 inline mr-1 text-[#6B4E71]') Email Address
                    </label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full px-4 py-3 bg-white rounded-xl shadow-inner focus:outline-none focus:ring-2 focus:ring-[#6B4E71]" />
                </div>

                {{-- Actions --}}
                <div class="pt-6 border-t border-[#6B4E71]/20 flex justify-between items-center">
                    <div>
                        <button type="button" id="deleteAccountBtn"
                            class="text-red-500 hover:text-red-700 flex items-center gap-2 text-sm font-medium">
                            @svg('heroicon-o-trash', 'w-5 h-5')
                            Delete Account
                        </button>
                    </div>
                    <div class="flex gap-4">
                        <button type="submit"
                            class="px-8 py-3 rounded-xl bg-gradient-to-r from-[#6B4E71] to-[#8D6595] text-white font-semibold shadow-md hover:opacity-90 transition">
                            Update Profile
                        </button>
                    </div>
                </div>
            </form>

            {{-- Delete Form --}}
            <form id="deleteForm" action="{{ route('user.profile.destroy') }}" method="POST">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteBtn = document.getElementById('deleteAccountBtn');
            const deleteForm = document.getElementById('deleteForm');

            deleteBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm(
                        'Are you sure you want to delete your account? This action cannot be undone.')) {
                    deleteForm.submit();
                }
            });
        });
    </script>
@endpush
