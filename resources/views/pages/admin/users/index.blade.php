@extends('layouts.admin')
@section('title', 'Users')

@php
    $filters = [
        [
            'name' => 'name',
            'type' => 'text',
            'placeholder' => 'Name',
            'icon' => 'heroicon-o-user',
        ],
        [
            'name' => 'email',
            'type' => 'text',
            'placeholder' => 'Email',
            'icon' => 'heroicon-o-envelope',
        ],
        [
            'name' => 'role',
            'type' => 'select',
            'label' => 'Role',
            'options' => ['admin', 'user'],
            'icon' => 'heroicon-o-identification',
        ],
    ];
@endphp

@section('content')
    <div class="flex flex-1 justify-between items-center bg-[#FFEBFA] p-4">
        <h1 class="text-2xl font-bold">Users</h1>
        <a href="{{ route('users.create') }}" class="bg-[#6B4E71] text-white px-4 py-2 rounded">Add User</a>
    </div>

    <div class="w-full relative z-20">
        <div class="max-w-7xl mx-auto p-4 sm:p-5">
            <div class="w-full relative z-20">
                <x-searchbar :filters="$filters" :action="route('admin.users.index')" />
            </div>
        </div>
    </div>

    <div class="flex flex-1 justify-center flex-wrap gap-4 p-4 sm:p-5">
        @forelse ($users as $user)
            <x-user-card :user="$user" />
        @empty
            <p class="text-gray-500">No users found.</p>
        @endforelse
    </div>

    <div class="w-full flex justify-center mt-6 py-6">
        <div class="max-w-sm sm:flex sm:flex-col">
            {{ $users->withQueryString()->links() }}
        </div>
    </div>
@endsection
