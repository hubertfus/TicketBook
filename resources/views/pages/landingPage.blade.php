@extends('layouts.user')

@section('title', 'Home')

@section('content')
    <div class="relative flex flex-col lg:flex-row h-full bg-white/90 lg:bg-transparent">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-10 lg:gap-20 py-10">
            <div class="lg:w-1/2 flex flex-col justify-center space-y-6 text-center lg:text-left relative z-10">
                <small class="text-gray-500 font-medium text-sm md:text-base">
                    Rozrywka na wyciągnięcie ręki – znajdź coś dla siebie
                </small>

                <h1 class="text-3xl md:text-5xl font-bold text-gray-900">
                    Odkrywaj najlepsze wydarzenia w swoim mieście
                </h1>

                <p class="text-gray-600 text-base md:text-lg">
                    Wybieraj spośród koncertów, spektakli, festiwali i wielu innych atrakcji.
                    Kup bilety szybko, wygodnie i bez wychodzenia z domu.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                    <a href="#"
                        class="bg-[#6B4E71] text-lg sm:text-xl text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#48354D] transition">
                        Kup bilet →
                    </a>
                    <a href="#" class="text-[#6B4E71] text-lg sm:text-xl font-semibold underline hover:no-underline">
                        Dowiedz się więcej
                    </a>
                </div>
            </div>

            <div class="hidden lg:block lg:w-1/2 absolute lg:h-full right-0">
                <img src="images/ticket.jpg" alt="Bilety"
                    class="absolute left-0 w-full h-full object-cover rounded-l-[4rem] top-16" />
            </div>
        </div>
    </div>

    <div class="w-full relative z-20" style="background: linear-gradient(to bottom, transparent 50%, #FFEBFA 50%);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
            <div class="max-w-4xl w-full relative z-20 mx-auto">
                @include('components.searchbar')
            </div>
        </div>
    </div>

    <div class="w-full bg-[#FFEBFA] py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <h1 class="text-3xl md:text-5xl font-bold text-black text-center mb-6">Tych koncertów nie możesz przegapić</h1>
            <small class="block text-sm md:text-base font-bold text-gray-500 text-center mb-8">
                Odkryj wydarzenia które warto przeżyć na żywo
            </small>
            @include('components.carousel', [
                'slides' => [
                    ['image' => 'images/ticket.jpg', 'alt' => 'Slide 1'],
                    ['image' => 'images/ticket.jpg', 'alt' => 'Slide 2'],
                    ['image' => 'images/ticket.jpg', 'alt' => 'Slide 3'],
                    ['image' => 'images/ticket.jpg', 'alt' => 'Slide 4'],
                    ['image' => 'images/ticket.jpg', 'alt' => 'Slide 5'],
                    ['image' => 'images/ticket.jpg', 'alt' => 'Slide 6'],
                ],
            ])
        </div>
    </div>

    <div class="w-full relative z-20" style="background: linear-gradient(to bottom, #FFEBFA 0%, #FFF7FD 100%);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">
            @include('components.comment')
        </div>
    </div>
@endsection
