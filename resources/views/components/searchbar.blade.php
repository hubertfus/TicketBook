@props(['filters' => [], 'action' => '#'])

@php
    $currentParams = request()->all();
@endphp

<form method="GET" action="{{ $action }}">
    <div class="bg-[#D7C1D3] p-6 rounded-3xl shadow-md">
        <div class="relative z-10 flex flex-wrap gap-3 p-3 bg-[#FFF7FD] border border-gray-200 rounded-lg shadow-inner">
            @foreach ($filters as $filter)
                @php
                    $value = request($filter['name']);
                    $paramsWithoutThis = collect($currentParams)->except($filter['name'])->toArray();
                    $clearUrl = $action . '?' . http_build_query($paramsWithoutThis);
                @endphp

                <div class="flex flex-1 items-center gap-2 w-full md:w-auto border-r-2 border-[#6B4E71] relative">
                    @if (!empty($filter['icon']))
                        <label class="block text-sm font-medium text-gray-600">
                            @svg($filter['icon'], 'h-5 w-5 flex-shrink-0 text-[#6B4E71]')
                        </label>
                    @endif

                    @switch($filter['type'])
                        @case('text')
                            <input type="text" name="{{ $filter['name'] }}" placeholder="{{ $filter['placeholder'] ?? '' }}"
                                value="{{ $value }}"
                                class="py-2.5 px-4 block w-full border border-transparent rounded-lg outline-0" />
                        @break

                        @case('date')
                            <input type="date" name="{{ $filter['name'] }}" value="{{ $value }}"
                                class="py-2.5 px-4 block w-full border border-transparent rounded-lg outline-0" />
                        @break

                        @case('select')
                            <select name="{{ $filter['name'] }}"
                                class="py-2.5 px-4 block w-full border border-transparent rounded-lg outline-0">
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
                        <a href="{{ $clearUrl }}"
                            class="absolute top-0 right-0 mt-1 mr-1 text-gray-500 hover:text-red-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>
            @endforeach

            <div class="flex items-center justify-center">
                <button type="submit">
                    <svg class="w-6 h-6 text-[#6B4E71]" xmlns="http://www.w3.org/2000/svg" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</form>
