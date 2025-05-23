<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Login')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#FFF7FD] flex flex-col items-center justify-center p-6">

    <div class="flex flex-1 w-full max-w-md bg-[#FFF7FD] rounded-lg p-6 justify-center items-center">
        <main class="w-full">
            @yield('content')
        </main>
    </div>

</body>
</html>
