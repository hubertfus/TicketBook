<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Panel')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#FFF7FD] h-screen w-screen flex overflow-hidden">

    {{-- Sidebar --}}
    @include('layouts.partials.sidebar')

    {{-- Page Content --}}
    <main class="flex-1 overflow-y-auto bg-[#FFF7FD]">
        @yield('content')
    </main>

</body>

</html>
