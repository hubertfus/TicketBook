@props(['filters' => [], 'action' => '#'])

@php
    $currentParams = request()->all();
@endphp

<form method="GET" action="{{ $action }}" class=" bg-[#FFEBFA] rounded-2xl shadow-lg p-5">
    <div class="flex flex-wrap gap-3 p-4 bg-[#FFF7FD] rounded-2xl shadow-inner">
        @foreach ($filters as $filter)
            @php
                $value = request($filter['name']);
                $paramsWithoutThis = collect($currentParams)->except($filter['name'])->toArray();
                $clearUrl = $action . '?' . http_build_query($paramsWithoutThis);
            @endphp

            <div class="relative flex items-center bg-white rounded-lg shadow-sm flex-1 min-w-[120px]">
                @if (!empty($filter['icon']))
                    <span class="absolute left-4 text-[#6B4E71]">
                        @svg($filter['icon'], 'h-5 w-5')
                    </span>
                @endif

                @switch($filter['type'])
                    @case('text')
                        <input type="text" name="{{ $filter['name'] }}" placeholder="{{ $filter['placeholder'] ?? '' }}"
                            value="{{ $value }}"
                            class="w-full py-3 pl-12 pr-10 bg-transparent text-[#3A4454] placeholder-[#3A4454] rounded-lg focus:outline-none text-base" />
                    @break

                    @case('date')
                        <input type="date" name="{{ $filter['name'] }}" value="{{ $value }}"
                            class="w-full py-3 pl-12 pr-10 bg-transparent text-[#3A4454] rounded-lg focus:outline-none text-base" />
                    @break

                    @case('select')
                        <select name="{{ $filter['name'] }}"
                            class="w-full py-3 pl-12 pr-10 bg-transparent text-[#3A4454] rounded-lg focus:outline-none text-base">
                            <option value="">-- {{ $filter['label'] ?? ucfirst($filter['name']) }} --</option>
                            @foreach ($filter['options'] as $option)
                                <option value="{{ $option }}" {{ $value == $option ? 'selected' : '' }}>
                                    {{ ucfirst($option) }}
                                </option>
                            @endforeach
                        </select>
                    @break
                @endswitch

                @if (!empty($value))
                    <a href="{{ $clearUrl }}" class="absolute right-3 text-gray-500 hover:text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                @endif
            </div>
        @endforeach

        <button type="submit"
            class="flex items-center justify-center w-12 h-12 bg-[#6B4E71] rounded-lg shadow-md hover:bg-[#593b5c] transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.3-4.3" />
            </svg>
        </button>
    </div>
</form>
