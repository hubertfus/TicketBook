<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Logowanie')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#FFF7FD] min-h-screen flex items-center justify-center">

    <main class="w-full max-w-md p-6 bg-[#FFEBFA] rounded-lg shadow-md">
        @yield('content')
    </main>

</body>
</html>
