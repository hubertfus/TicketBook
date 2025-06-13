<div
    class="bg-[#FFEBFA] rounded-2xl p-6 shadow-lg w-full max-w-xs flex flex-col gap-4 transition-all hover:shadow-md hover:-translate-y-1">
    {{-- User Info --}}
    <div class="flex items-start gap-4">
        <div
            class="h-12 w-12 flex-shrink-0 flex items-center justify-center rounded-full bg-white border-2 border-[#D7C1D3]">
            @svg('heroicon-o-user', 'h-6 w-6 text-[#6B4E71]')
        </div>

        <div class="flex-1 min-w-0">
            <h3 class="text-lg font-bold text-[#3A4454] truncate flex items-center gap-2">
                {{ $user->name }}
                @if ($user->role === 'admin')
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-[#6B4E71] text-white">
                        Admin
                    </span>
                @endif
            </h3>
            <div class="flex items-center gap-2 text-sm text-[#6B4E71] mt-1">
                @svg('heroicon-o-envelope', 'h-4 w-4 flex-shrink-0')
                <p class="truncate" title="{{ $user->email }}">{{ $user->email }}</p>
            </div>
        </div>
    </div>

    {{-- Admin Actions --}}
    @if (auth()->check() && auth()->user()->role === 'admin')
        <div class="flex justify-end gap-3 pt-4 mt-2 border-t border-[#6B4E71]/20">
            <a href="{{ route('admin.users.edit', $user) }}"
                class="flex items-center gap-1 px-3 py-1.5 text-sm rounded-lg text-[#6B4E71] hover:bg-[#6B4E71]/10 hover:text-[#6B4E71] transition-colors">
                @svg('heroicon-o-pencil', 'h-4 w-4')
                Edit
            </a>

            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                onsubmit="return confirm('Are you sure you want to delete this user?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="flex items-center gap-1 px-3 py-1.5 text-sm rounded-lg text-red-600 hover:bg-red-50 transition-colors">
                    @svg('heroicon-o-trash', 'h-4 w-4')
                    Delete
                </button>
            </form>
        </div>
    @endif
</div>
