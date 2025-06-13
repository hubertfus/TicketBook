@extends('layouts.admin')
@section('title', 'Create Review')

@section('content')
<div class="min-h-screen bg-[#FFF7FD] py-10 px-4">
    <div class="max-w-4xl mx-auto bg-[#FFEBFA] rounded-2xl shadow-lg p-8">
        <h2 class="text-3xl font-extrabold text-[#3A4454] flex items-center gap-2 mb-8">
            @svg('heroicon-o-plus-circle', 'w-6 h-6 text-[#6B4E71]')
            Create New Review
        </h2>

        <form action="{{ route('admin.reviews.store') }}" method="POST" class="bg-[#FFEBFA] rounded-2xl p-6">
            @csrf

            <div class="space-y-8">
                <!-- User and Event Selection -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- User Selection -->
                    <div>
                        <label for="user_id" class="block text-lg font-semibold text-[#3A4454] mb-3">
                            @svg('heroicon-o-user', 'w-5 h-5 inline mr-1 text-[#6B4E71]') User
                        </label>
                        <select name="user_id" id="user_id" required
                            class="w-full px-4 py-3 bg-white rounded-xl border border-[#D7C1D3] focus:ring-[#6B4E71] focus:border-[#6B4E71]">
                            <option value="">Select user</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Event Selection -->
                    <div>
                        <label for="event_id" class="block text-lg font-semibold text-[#3A4454] mb-3">
                            @svg('heroicon-o-calendar', 'w-5 h-5 inline mr-1 text-[#6B4E71]') Event
                        </label>
                        <select name="event_id" id="event_id" required
                            class="w-full px-4 py-3 bg-white rounded-xl border border-[#D7C1D3] focus:ring-[#6B4E71] focus:border-[#6B4E71]">
                            <option value="">Select event</option>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                    {{ $event->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('event_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Rating -->
                <div>
                    <label class="block text-lg font-semibold text-[#3A4454] mb-4">
                        @svg('heroicon-o-star', 'w-5 h-5 inline mr-1 text-[#6B4E71]') Rating
                    </label>
                    <div class="flex items-center gap-2 mb-2" id="rating-container">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="cursor-pointer">
                                <input type="radio" name="rating" value="{{ $i }}"
                                       class="sr-only peer"
                                       {{ old('rating') == $i ? 'checked' : '' }}>
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
                <div>
                    <label for="comment" class="block text-lg font-semibold text-[#3A4454] mb-4">
                        @svg('heroicon-o-chat-bubble-left-ellipsis', 'w-5 h-5 inline mr-1 text-[#6B4E71]') Comment
                    </label>
                    <textarea name="comment" id="comment" rows="5"
                        class="w-full px-4 py-3 bg-white rounded-xl border border-[#D7C1D3] focus:ring-[#6B4E71] focus:border-[#6B4E71]">{{ old('comment') }}</textarea>
                    @error('comment')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-4 pt-6 border-t border-[#6B4E71]/20">
                    <a href="{{ route('admin.reviews.index') }}"
                       class="px-6 py-3 rounded-xl bg-transparent border border-[#6B4E71] text-[#6B4E71] hover:bg-[#6B4E71] hover:text-white transition">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-8 py-3 rounded-xl bg-gradient-to-r from-[#6B4E71] to-[#8D6595] text-white font-semibold shadow-md hover:opacity-90 transition">
                        Create Review
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ratingContainer = document.getElementById('rating-container');
    const starIcons = ratingContainer.querySelectorAll('.star-icon');

    function updateStars(selectedValue) {
        starIcons.forEach(star => {
            const starValue = parseInt(star.getAttribute('data-value'));
            star.classList.toggle('text-yellow-400', starValue <= selectedValue);
            star.classList.toggle('text-gray-300', starValue > selectedValue);
        });
    }

    // Initialize with old rating or default
    updateStars({{ old('rating', 0) }});

    // Event listeners
    starIcons.forEach(star => {
        star.addEventListener('click', () => {
            const value = parseInt(star.getAttribute('data-value'));
            ratingContainer.querySelector(`input[value="${value}"]`).checked = true;
            updateStars(value);
        });

        star.addEventListener('mouseover', () => {
            updateStars(parseInt(star.getAttribute('data-value')));
        });
    });

    ratingContainer.addEventListener('mouseleave', () => {
        const checkedInput = ratingContainer.querySelector('input:checked');
        updateStars(checkedInput ? parseInt(checkedInput.value) : {{ old('rating', 0) }});
    });
});
</script>
@endsection
