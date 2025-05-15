@php
    $navItems = [
        ['name' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'heroicon-o-home'],
        ['name' => 'Transactions', 'route' => 'admin.dashboard', 'icon' => 'heroicon-m-arrows-right-left'],
        ['name' => 'Reports', 'route' => 'admin.dashboard', 'icon' => 'heroicon-o-document-text'],
        ['name' => 'Users', 'route' => 'admin.dashboard', 'icon' => 'heroicon-o-users'],
        ['name' => 'Events', 'route' => 'admin.dashboard', 'icon' => 'heroicon-o-calendar-days'],
        ['name' => 'Support', 'route' => 'admin.dashboard', 'icon' => 'heroicon-o-chat-bubble-left-right'],
    ];
@endphp


<div class="z-50 w-64 bg-[#FFEBFA] transition duration-300 ease-in-out transform shadow-[#FFEBFA] shadow-2xl ">
    {{-- logo --}}
    <div class="flex flex-1 py-10 px-5">
        <div class="flex flex-row text-3xl">
            <p>Ticket</p>
            <p class="font-extrabold">Book</p>
        </div>
    </div>

    {{-- navigation --}}
    <div class="flex flex-1 px-5 py-4 border-t-[#6B4E71] border-t-2">
        <ul class="flex flex-1 flex-col gap-2">
            @foreach ($navItems as $item)
                <li class="flex flex-row gap-1 cursor-pointer hover:bg-[#D7C1D3] py-3 px-2 rounded-sm">
                    @svg($item['icon'], 'mr-3 h-5 w-5 flex-shrink-0')

                    <div>{{ $item['name'] }}</div>
                </li>
            @endforeach
        </ul>
    </div>
</div>
