@extends('layouts.admin')
@section('title', 'Edit Review')

@section('content')
<div class="min-h-screen bg-[#FFF7FD] py-10 px-4">
    <div class="max-w-4xl mx-auto bg-[#FFEBFA] rounded-2xl shadow-lg p-8">
        <h2 class="text-3xl font-extrabold text-[#3A4454] flex items-center gap-2 mb-8">
            @svg('heroicon-o-pencil-square', 'w-6 h-6 text-[#6B4E71]')
            Edit Review
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- User Info Section -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-xl shadow-inner p-6">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="h-14 w-14 rounded-full bg-[#FFEBFA] border-2 border-[#D7C1D3] flex items-center justify-center">
                            @svg('heroicon-o-user', 'h-6 w-6 text-[#6B4E71]')
                        </div>
                        <div>
                            <h3 class="font-bold text-[#3A4454]">{{ $review->user->name }}</h3>
                            <p class="text-sm text-[#6B4E71]">{{ $review->user->email }}</p>
                        </div>
                    </div>

                    <div class="space-y-4 text-sm text-[#3A4454]">
                        <div class="flex items-center gap-3">
                            @svg('heroicon-o-calendar', 'h-4 w-4 text-[#6B4E71]')
                            <span>Review date: {{ $review->created_at->format('d.m.Y H:i') }}</span>
                        </div>

                        <div class="pt-4 border-t border-[#6B4E71]/20">
                            <h4 class="font-semibold text-[#3A4454] mb-2">Event</h4>
                            <a href="{{ route('admin.events.show', $review->event) }}"

                               class="text-[#6B4E71] hover:underline font-medium">
                                {{ $review->event->title }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Review Form Section -->
            <div class="md:col-span-2">
                <form action="{{ route('admin.reviews.update', $review) }}" method="POST" class="bg-[#FFEBFA] rounded-2xl p-6">
                    @csrf
                    @method('PUT')

                    <!-- Rating -->
                    <div class="mb-8">
                        <label class="block text-lg font-semibold text-[#3A4454] mb-4">Rating</label>
                        <div class="flex items-center gap-2 mb-2" id="rating-container">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer">
                                    <input type="radio" name="rating" value="{{ $i }}"
                                           class="sr-only peer"
                                           {{ $review->rating == $i ? 'checked' : '' }}>
                                    <span class="text-3xl text-gray-300 star-icon hover:text-yellow-400 transition-colors duration-200"
                                          data-value="{{ $i }}">
                                        @svg('heroicon-s-star', 'h-8 w-8 fill-current')
                                    </span>
                                </label>
                            @endfor
                        </div>
                        @error('rating')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Comment -->
                    <div class="mb-8">
                        <label for="comment" class="block text-lg font-semibold text-[#3A4454] mb-4">
                            Comment
                        </label>
                        <textarea name="comment" id="comment" rows="5"
                            class="bg-white w-full px-4 py-3 bg-[#FFEBFA] rounded-xl border border-[#D7C1D3] focus:ring-[#6B4E71] focus:border-[#6B4E71]">{{ old('comment', $review->comment) }}</textarea>
                        @error('comment')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end gap-4 pt-6 border-t border-[#6B4E71]/20">
                        <a href="{{ route('admin.reviews.index') }}"
                           class="px-6 py-3 rounded-xl bg-transparent border border-[#6B4E71] text-[#6B4E71] hover:bg-[#6B4E71] hover:text-white transition">
                            Cancel
                        </a>
                        <button type="submit"
                                class="px-8 py-3 rounded-xl bg-gradient-to-r from-[#6B4E71] to-[#8D6595] text-white font-semibold shadow-md hover:opacity-90 transition">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ratingContainer = document.getElementById('rating-container');
    const starIcons = ratingContainer.querySelectorAll('.star-icon');
    const radioInputs = ratingContainer.querySelectorAll('input[type="radio"]');

    function updateStars(selectedValue) {
        starIcons.forEach(star => {
            const starValue = parseInt(star.getAttribute('data-value'));
            if (starValue <= selectedValue) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            }
        });
    }

    const currentRating = {{ $review->rating }};
    updateStars(currentRating);

    starIcons.forEach(star => {
        star.addEventListener('click', function() {
            const value = parseInt(this.getAttribute('data-value'));
            const radioInput = ratingContainer.querySelector(`input[value="${value}"]`);
            radioInput.checked = true;
            updateStars(value);
        });
    });

    starIcons.forEach(star => {
        star.addEventListener('mouseover', function() {
            const hoverValue = parseInt(this.getAttribute('data-value'));
            updateStars(hoverValue);
        });
    });

    ratingContainer.addEventListener('mouseleave', function() {
        const checkedInput = ratingContainer.querySelector('input[type="radio"]:checked');
        if (checkedInput) {
            updateStars(parseInt(checkedInput.value));
        }
    });
});
</script>
@endsection
