<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Panel')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#FFF7FD] h-screen flex overflow-hidden">

    {{-- Sidebar --}}
    @include('layouts.partials.sidebar')

    {{-- Main content --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Navbar --}}
        @include('layouts.partials.navbar')

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 bg-[#FFF7FD]">
            @yield('content')
        </main>
    </div>

</body>

</html>
