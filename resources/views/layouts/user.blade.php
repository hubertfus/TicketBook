<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'app')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />

    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

</head>

<body class="bg-[#FFF7FD] h-screen flex flex-1 flex-col overflow-x-hidden">

    {{-- Header --}}
    @include('layouts.partials.header')

    {{-- Page content --}}
    <div class="flex flex-1 w-full bg-[#FFF7FD] rounded-lg justify-center items-center">
        <main class="w-full">
            @yield('content')
        </main>
    </div>

    {{-- Footer --}}
    @include('layouts.partials.footer')

    @stack('scripts')

</body>

</html>
