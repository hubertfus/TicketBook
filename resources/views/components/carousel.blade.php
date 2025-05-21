<style>
    .swiper-button-prev,
    .swiper-button-next {
        color: #6B4E71;
        position: relative;
        top: auto;
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

    .swiper-pagination {
        position: relative;
        margin-top: 1rem;
        bottom: auto;
    }

    .swiper-pagination-bullet {
        background-color: #d1d5db;
        opacity: 1;
    }

    .swiper-pagination-bullet-active {
        background-color: #6B4E71;
    }

    .swiper-slide {
        position: relative;
        overflow: hidden;
    }

    .swiper-slide::after {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        top: 0;
        pointer-events: none;
        border-radius: 0.5rem;
        mix-blend-mode: screen;
        transition: background 0.3s ease;
    }

    .swiper-slide img {
        width: 100%;
        height: auto;
        max-height: 24rem;
        object-fit: cover;
        border-radius: 0.5rem;
    }

    .swiper-container {
        width: 100%;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .swiper {
        width: 100%;
    }

    @media (max-width: 639px) {

        .swiper-button-prev,
        .swiper-button-next {
            display: none;
        }
    }
</style>

<div class="relative max-w-7xl flex items-center mb-4">
    <button class="swiper-button-prev focus:outline-none" aria-label="Previous slide"></button>

    <div class="swiper-container">
        <div class="swiper rounded-lg p-6 flex-1">
            <div class="swiper-wrapper">
                @foreach ($slides as $slide)
                    <div class="swiper-slide flex justify-center items-center">
                        <img src="{{ asset($slide['image']) }}" alt="{{ $slide['alt'] ?? 'Slide' }}"
                            class="rounded-lg object-cover" />
                    </div>
                @endforeach
            </div>
        </div>
        <div class="swiper-pagination"></div>
    </div>

    <button class="swiper-button-next focus:outline-none" aria-label="Next slide"></button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        new Swiper(".swiper", {
            slidesPerView: 1,
            spaceBetween: 16,
            loop: true,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                640: {
                    slidesPerView: 1,
                    spaceBetween: 16,
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 24,
                },
            },
        });
    });
</script>
