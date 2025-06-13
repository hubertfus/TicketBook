@php
    use Illuminate\Support\Facades\Storage;
@endphp

<style>
    .carousel-container {
        position: relative;
        width: 100%;
    }

    .swiper {
        width: 100%;
        padding: 0 3rem;
    }

    .swiper-wrapper {
        display: flex;
        align-items: stretch;
    }

    .swiper-slide {
        height: auto;
        display: flex;
        align-items: stretch;
    }

    .swiper-slide>div {
        width: 100%;
        display: flex;
    }

    .carousel-card {
        width: 100%;
        display: flex;
    }

    .swiper-button-prev,
    .swiper-button-next {
        color: #6B4E71;
        position: absolute;
        top: 50%;
        transform: none;
        width: 3.5rem;
        height: 3.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        flex-shrink: 0;
        z-index: 10;
    }

    .swiper-button-prev {
        left: 0.5rem;
    }

    .swiper-button-next {
        right: 0.5rem;
    }

    .swiper-button-prev:after,
    .swiper-button-next:after {
        font-size: 1.25rem;
        font-weight: bold;
    }

    .swiper-button-disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .swiper-pagination {
        position: relative;
        margin-top: 2rem;
        bottom: auto;
    }

    .swiper-pagination-bullet {
        background-color: #d1d5db;
        opacity: 1;
        width: 8px;
        height: 8px;
    }

    .swiper-pagination-bullet-active {
        background-color: #6B4E71;
    }

    @media (max-width: 639px) {
        .swiper {
            padding: 0;
        }

        .swiper-button-prev,
        .swiper-button-next {
            display: none;
        }
    }
</style>

<div class="carousel-container">
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            @foreach ($events as $event)
                <div class="swiper-slide">
                    <div class="carousel-card">
                        {{-- Karta wydarzenia --}}
                        <x-event-card :event="$event" class="carousel-card" />
                    </div>
                </div>
            @endforeach
        </div>
        <div class="swiper-pagination"></div>
    </div>

    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const swiper = new Swiper('.mySwiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: false,
            watchOverflow: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                640: {
                    slidesPerView: 1,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 24,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 50,
                },
            },
            on: {
                init: function() {
                    this.updateAutoHeight(0);
                },
                slideChange: function() {
                    this.updateAutoHeight(300);
                }
            }
        });
    });
</script>
