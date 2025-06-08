@php
    $navItems = [
        ['name' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'heroicon-o-home'],
        ['name' => 'Transactions', 'route' => 'orders.index', 'icon' => 'heroicon-m-arrows-right-left'],
        ['name' => 'Refunds', 'route' => 'refunds.index', 'icon' => 'heroicon-o-receipt-refund'],
        ['name' => 'Reports', 'route' => 'events.index', 'icon' => 'heroicon-o-document-text'],
        ['name' => 'Users', 'route' => 'users.index', 'icon' => 'heroicon-o-users'],
        ['name' => 'Events', 'route' => 'events.index', 'icon' => 'heroicon-o-calendar-days'],
        ['name' => "Reviews", 'route' => 'admin.reviews.index', 'icon' => 'heroicon-o-chat-bubble-bottom-center-text'],
        ['name' => 'Support', 'route' => 'events.index', 'icon' => 'heroicon-o-chat-bubble-left-right'],
        ['name' => 'Top-Up Generator', 'route' => 'admin.topup.create', 'icon' => 'heroicon-o-currency-dollar'],
    ];
@endphp

<div id="sidebar" class="z-50 lg:w-64 bg-[#FFEBFA] shadow-[#FFEBFA] shadow-2xl">
    {{-- logo --}}
    <div class="flex flex-1 flex-col py-10 px-5 gap-3 align-center">
        <div class="hidden flex-row text-3xl lg:flex">
            <p>Ticket</p>
            <p class="font-extrabold">Book</p>
        </div>
        <div class="flex flex-1 justify-center flex-row text-3xl lg:hidden">
            <p>T</p>
            <p class="font-extrabold">B</p>
        </div>
    </div>

    {{-- navigation --}}
    <div class="flex flex-1 px-5 py-4 border-t-[#6B4E71] border-t-2">
        <ul class="flex flex-1 flex-col gap-2">
            @foreach ($navItems as $item)
                <li class="flex flex-row gap-1 cursor-pointer hover:bg-[#D7C1D3] py-3 px-2 rounded-sm">
                    <a href="{{ route($item['route']) }}" class="flex flex-row gap-2 items-center w-full">
                        @svg($item['icon'], 'h-5 w-5 flex-shrink-0')
                        <div class="hidden lg:flex">{{ $item['name'] }}</div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>

<div>
    <div id="toggleButton"
        class="flex aligin-items-center my-48 lg:hidden bg-[#FFEBFA] py-5 px-2 absolute rounded-r-2xl cursor-pointer shadow-2xl shadow-[#6B4E71]">
        @svg('heroicon-o-arrow-right-circle', 'icon-toggle h-5 w-5 flex-shrink-0 transition-transform duration-300')
    </div>
</div>

<script>
    const toggleButton = document.getElementById('toggleButton');
    const sidebar = document.getElementById('sidebar');
    const iconToggle = document.querySelector('.icon-toggle');

    toggleButton.addEventListener('click', () => {
        sidebar.classList.toggle('lg:w-64');
        sidebar.classList.toggle('lg:w-16');


        iconToggle.classList.toggle('rotate-180');

        sidebar.querySelectorAll('ul li div').forEach(el => {
            el.classList.toggle('hidden');
        });
    });
</script>
