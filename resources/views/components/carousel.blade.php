<style>
    .carousel-wrapper {
        position: relative;
        width: 100%;
        padding: 0 0rem;
    }

    .carousel-container {
        position: relative;
        width: 100%;
        overflow: hidden;
        padding: 2rem 0;
        margin: -2rem 0.75rem;
    }

    .swiper {
        width: 100%;
        padding: 0;
        overflow: visible;
    }

    .swiper-wrapper {
        display: flex;
        align-items: stretch;
    }

    .swiper-slide {
        height: auto;
        display: flex;
        align-items: stretch;
        margin-right: 0px !important;
    }

    .swiper-slide>div {
        width: 100%;
        display: flex;
    }

    .carousel-card {
        width: 100%;
        display: flex;
        transform: scale(0.9);
    }

    .swiper-button-prev,
    .swiper-button-next {
        color: #6B4E71;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 3.5rem;
        height: 3.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        flex-shrink: 0;
        z-index: 10;
        overflow: visible;
    }

    .swiper-button-prev {
        left: -2rem;
    }

    .swiper-button-next {
        right: -2rem;
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
        .carousel-wrapper {
            padding: 0;
        }

        .swiper-button-prev,
        .swiper-button-next {
            display: none;
        }
    }
</style>

<div class="carousel-wrapper">
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
    </div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const swiper = new Swiper('.mySwiper', {
            slidesPerView: 1,
            spaceBetween: 10,
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
                    spaceBetween: 0,
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 0,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 0,
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
