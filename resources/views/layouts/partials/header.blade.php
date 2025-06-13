<header
    class="flex flex-wrap items-center bg-[#FFEBFA] backdrop-blur-sm shadow-[0_6px_8px_rgba(230,210,225,0.5)] py-5 px-6 z-50">


    <nav class="flex flex-wrap items-center justify-between w-full max-w-7xl mx-auto gap-6">

        <!-- Logo -->
        <div class="flex-shrink-0">
            <a href="{{ url('/') }}" class="text-4xl text-black tracking-tight" aria-label="Brand">
                <span class="font-normal">Ticket</span><span class="font-bold">Book</span>
            </a>
        </div>

        <!-- Navigation Links -->
        <div class="flex items-center gap-8 flex-1 justify-end">
            <a class="text-lg font-medium text-black hover:underline transition"
                href="{{ route('user.events.index', ['filter' => 'trending']) }}">Trending</a>
            <a class="text-lg font-medium text-black hover:underline transition"
                href="{{ route('user.events.index', ['filter' => 'new']) }}">Newly Added</a>
            <a class="text-lg font-medium text-black hover:underline transition"
                href="{{ route('user.events.index', ['filter' => 'this-weekend']) }}">This Weekend</a>
        </div>

        <!-- User Panel -->
        <div class="flex items-center gap-5">
            @if (!Auth::check())
                <a href="/register" class="text-black font-medium hover:underline transition">Register</a>
                <a href="/login"
                    class="bg-[#6B4E71] text-white px-5 py-2 rounded-xl font-medium hover:bg-[#48354D] transition">Log
                    In →</a>
            @else
                <a href="#" class="text-lg text-black font-medium hover:underline transition">Favorites</a>
                <div class="relative">
                    <button onclick="toggleDropdown()" class="p-2 focus:outline-none" aria-label="User Menu">
                        <svg class="w-7 h-7 text-[#6B4E71] hover:text-[#48354D]" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M5.121 17.804A9 9 0 0112 15a9 9 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </button>

                    <div id="userDropdown"
                        class="hidden overflow-hidden absolute right-0 mt-2 w-64 bg-[#FFEBFA] border border-[#D7C1D3] rounded-xl shadow-xl z-50 text-base">
                        <a href="{{ route('user.orders.index') }}"
                            class="block px-5 py-3 text-black hover:bg-[#D7C1D3]">Transactions / Tickets</a>
                        <a href="{{ route('user.profile.edit') }}"
                            class="block px-5 py-3 text-black hover:bg-[#D7C1D3]">Account</a>
                        <a href="{{ route('user.topup.index') }}"
                            class="block px-5 py-3 text-black hover:bg-[#D7C1D3]">My
                            Top-Up Codes</a>
                        <a href="{{ route('logout') }}" class="block px-5 py-3 text-black hover:bg-[#D7C1D3]">Log
                            Out</a>
                        <a href="{{ route('user.profile.destroy') }}"
                            class="block px-5 py-3 text-red-600 hover:bg-[#D7C1D3]">Delete Account</a>
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
