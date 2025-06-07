<div class="bg-[#FFEBFA] rounded-lg p-4 shadow-sm w-72 flex flex-col justify-between">
    <div class="mb-2">
        <h3 class="text-lg font-semibold text-[#3A4454] truncate">{{ $user->name }}</h3>
        <p class="text-sm text-[#6B4E71] capitalize">{{ $user->role }}</p>
    </div>

    <p class="text-sm text-[#3A4454] mb-2 truncate" title="{{ $user->email }}">
        {{ $user->email }}
    </p>

    @if (auth()->check() && auth()->user()->role === 'admin')
        <div class="flex justify-end space-x-4 mt-4 text-sm">
            <a href="{{ route('users.edit', $user) }}"
               class="text-[#6B4E71] hover:underline hover:font-semibold">Edit</a>

            <form action="{{ route('users.destroy', $user) }}" method="POST"
                  onsubmit="return confirm('Are you sure you want to delete this user?');" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="text-[#6B4E71] hover:underline hover:font-semibold">Delete</button>
            </form>
        </div>
    @endif
</div>
