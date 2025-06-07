@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
    <div class="max-w-xl mx-auto my-8">
        <h2 class="text-2xl font-bold mb-4">Edit User</h2>

        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif


            <div class="mb-4">
                <label class="block font-medium mb-1" for="name">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                    class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1" for="email">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                    class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1" for="role">Role</label>
                <select name="role" id="role" class="w-full border rounded p-2" required>
                    <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1" for="password">Password <small class="text-gray-500">(leave blank to keep current)</small></label>
                <input type="password" name="password" id="password" class="w-full border rounded p-2" autocomplete="new-password">
            </div>

            <div class="flex gap-4">
                <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 transition">
                    Update User
                </button>
                <a href="{{ route('users.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
