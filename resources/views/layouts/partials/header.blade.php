<header class="flex flex-wrap items-center bg-[#FFEBFA] text-sm py-6 px-4">
    <nav class="flex flex-wrap items-center justify-between w-full max-w-7xl mx-auto gap-6 sm:gap-8">

        <div class="flex-shrink-0">
            <a href="#" class="text-2xl text-black dark:text-black" aria-label="Brand">
                <span class="font-normal text-5xl">Ticket</span><span class="font-bold text-5xl">Book</span>
            </a>
        </div>

        <div class="flex items-center gap-5 sm:gap-7 flex-1 justify-center sm:justify-start">
            <a class="font-medium text-xl text-black hover:font-bold" href="#">Na czasie</a>
            <a class="font-medium text-xl text-black hover:font-bold" href="#">Nowo dodane</a>
            <a class="font-medium text-xl text-black hover:font-bold" href="#">W ten weekend</a>
        </div>

        <div
            class="flex flex-wrap items-center gap-4 sm:gap-6 justify-center sm:justify-end flex-shrink-0 w-full sm:w-auto mt-4 sm:mt-0">
            @if (!Auth::check())
                <a href="/register" class="text-black text-xl font-semibold underline hover:no-underline">Zarejestruj
                    się</a>
                <a href="login"
                    class="bg-[#6B4E71] text-xl text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#48354D] transition">Zaloguj
                    się →</a>
            @else
                <a href="#" class="font-medium text-xl text-black hover:font-bold">Polubione</a>
                <div class="relative">
                    <button onclick="toggleDropdown()" class="p-2 focus:outline-none" aria-label="Menu użytkownika">
                        <svg class="w-8 h-8 text-[#6B4E71] hover:text-[#48354D]" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M5.121 17.804A9 9 0 0112 15a9 9 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>

                    <div id="userDropdown"
                        class="hidden absolute right-0 mt-2 w-72 bg-[#FFEBFA] border border-gray-200 rounded-xl shadow-2xl z-50 text-lg">
                        <a href="{{ route('user.orders.index') }}" class="block px-6 py-3 text-black hover:bg-[#D7C1D3]">Transakcje / bilety</a>
                        <a href="#" class="block px-6 py-3 text-black hover:bg-[#D7C1D3]">Dane kontaktowe</a>
                        <a href="{{ route('logout') }}" class="block px-6 py-3 text-black hover:bg-[#D7C1D3]">Wyloguj
                            się</a>
                        <a href="#" class="block px-6 py-3 text-red-600 hover:bg-[#D7C1D3]">Usuń konto</a>
                    </div>
                </div>
            @endif
        </div>

    </nav>
</header>

<script>
    function toggleDropdown() {
        const dropdown = document.getElementById('userDropdown');
        dropdown.classList.toggle('hidden');
    }

    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('userDropdown');
        const button = event.target.closest('button');

        if (!event.target.closest('#userDropdown') && !button) {
            dropdown.classList.add('hidden');
        }
    });
</script>
