@extends('layouts.admin')

@section('content')
    @if ($errors->any())
        <div class="bg-red-100 text-red-600 p-4 mb-4 rounded-2xl shadow-inner">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="min-h-screen bg-[#FFF7FD] py-10 px-4">
        <div class="max-w-3xl mx-auto bg-[#FFEBFA] rounded-2xl shadow-lg p-8">
            <h2 class="text-3xl font-extrabold text-[#3A4454] flex items-center gap-2 mb-6">
                @svg('heroicon-o-pencil-square', 'w-6 h-6 text-[#6B4E71]') Edit Event
            </h2>

            <form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Title & Type --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-[#3A4454] mb-2">
                            @svg('heroicon-o-tag', 'w-4 h-4 inline mr-1 text-[#6B4E71]') Title
                        </label>
                        <input type="text" name="title" value="{{ old('title', $event->title) }}" required
                            class="w-full px-4 py-3 bg-white rounded-xl shadow-inner focus:outline-none focus:ring-2 focus:ring-[#6B4E71]" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#3A4454] mb-2">
                            @svg('heroicon-o-information-circle', 'w-4 h-4 inline mr-1 text-[#6B4E71]') Type
                        </label>
                        <select name="type" required
                            class="w-full px-4 py-3 bg-white rounded-xl shadow-inner focus:outline-none focus:ring-2 focus:ring-[#6B4E71]">
                            <option value="">Select Type</option>
                            <option value="concert" {{ old('type', $event->type) == 'concert' ? 'selected' : '' }}>Concert
                            </option>
                            <option value="sport" {{ old('type', $event->type) == 'sport' ? 'selected' : '' }}>Sport
                            </option>
                            <option value="standup" {{ old('type', $event->type) == 'standup' ? 'selected' : '' }}>Stand-up
                            </option>
                            <option value="festival" {{ old('type', $event->type) == 'festival' ? 'selected' : '' }}>
                                Festival</option>
                            <option value="other" {{ old('type', $event->type) == 'other' ? 'selected' : '' }}>Other
                            </option>
                        </select>
                    </div>
                </div>

                {{-- Date & Time --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-[#3A4454] mb-2">
                            @svg('heroicon-o-calendar-days', 'w-4 h-4 inline mr-1 text-[#6B4E71]') Date
                        </label>
                        <input type="date" name="date"
                            value="{{ old('date', \Carbon\Carbon::parse($event->date)->format('Y-m-d')) }}" required
                            class="w-full px-4 py-3 bg-white rounded-xl shadow-inner focus:outline-none focus:ring-2 focus:ring-[#6B4E71]" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#3A4454] mb-2">
                            @svg('heroicon-o-clock', 'w-4 h-4 inline mr-1 text-[#6B4E71]') Time
                        </label>
                        <input type="time" name="time"
                            value="{{ old('time', \Carbon\Carbon::parse($event->time)->format('H:i')) }}" required
                            class="w-full px-4 py-3 bg-white rounded-xl shadow-inner focus:outline-none focus:ring-2 focus:ring-[#6B4E71]" />
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-sm font-medium text-[#3A4454] mb-2">
                        @svg('heroicon-o-document-text', 'w-4 h-4 inline mr-1 text-[#6B4E71]') Description
                    </label>
                    <textarea name="description" rows="4" required
                        class="w-full px-4 py-3 bg-white rounded-2xl shadow-inner focus:outline-none focus:ring-2 focus:ring-[#6B4E71]">{{ old('description', $event->description) }}</textarea>
                </div>

                {{-- Organizer & Location --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-[#3A4454] mb-2">
                            @svg('heroicon-o-user', 'w-4 h-4 inline mr-1 text-[#6B4E71]') Organizer
                        </label>
                        <input type="text" name="organizer" value="{{ old('organizer', $event->organizer) }}" required
                            class="w-full px-4 py-3 bg-white rounded-xl shadow-inner focus:outline-none focus:ring-2 focus:ring-[#6B4E71]" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#3A4454] mb-2">
                            @svg('heroicon-o-map-pin', 'w-4 h-4 inline mr-1 text-[#6B4E71]') Location
                        </label>
                        <input type="text" name="location" value="{{ old('location', $event->location) }}" required
                            class="w-full px-4 py-3 bg-white rounded-xl shadow-inner focus:outline-none focus:ring-2 focus:ring-[#6B4E71]" />
                    </div>
                </div>

                {{-- Tickets --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-[#3A4454] mb-2">
                            @svg('heroicon-o-user-group', 'w-4 h-4 inline mr-1 text-[#6B4E71]') Total Tickets
                        </label>
                        <input type="number" name="totalTickets" value="{{ old('totalTickets', $event->totalTickets) }}"
                            required
                            class="w-full px-4 py-3 bg-white rounded-xl shadow-inner focus:outline-none focus:ring-2 focus:ring-[#6B4E71]" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#3A4454] mb-2">
                            @svg('heroicon-o-ticket', 'w-4 h-4 inline mr-1 text-[#6B4E71]') Tickets Sold
                        </label>
                        <input type="number" name="ticketSold" value="{{ old('ticketSold', $event->ticketSold) }}"
                            class="w-full px-4 py-3 bg-white rounded-xl shadow-inner focus:outline-none focus:ring-2 focus:ring-[#6B4E71]" />
                    </div>
                </div>

                {{-- Image Upload --}}
                <div>
                    <label class="block text-sm font-medium text-[#3A4454] mb-2">
                        @svg('heroicon-o-arrow-down-tray', 'w-4 h-4 inline mr-1 text-[#6B4E71]') Event Image
                    </label>
                    <div class="relative w-full h-48 rounded-2xl shadow-inner overflow-hidden border-2 border-dashed border-[#6B4E71] flex items-center justify-center cursor-pointer"
                        onclick="document.getElementById('imageInput').click()">
                        <input type="file" name="image" id="imageInput" accept="image/*"
                            class="absolute inset-0 opacity-0 cursor-pointer" />
                        <span id="uploadPlaceholder"
                            class="text-[#6B4E71] opacity-50 {{ $event->image ? 'hidden' : 'block' }}">
                            @svg('heroicon-o-arrow-down-tray', 'w-12 h-12')
                        </span>
                        <img id="imagePreview" src="{{ $event->image ? asset('storage/' . $event->image) : '#' }}"
                            alt="Preview"
                            class="absolute inset-0 w-full h-full object-cover {{ $event->image ? 'block' : 'hidden' }}" />
                        <div id="changeOverlay"
                            class="absolute inset-0 bg-black/40 flex items-center justify-center text-white text-sm font-medium opacity-0 hover:opacity-100 transition-opacity">
                            Change Image
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="pt-6 border-t border-[#6B4E71]/20 flex justify-end gap-4">
                    <a href="{{ route('admin.events.index') }}"
                        class="px-6 py-3 rounded-xl bg-transparent border border-[#6B4E71] text-[#6B4E71] hover:bg-[#6B4E71] hover:text-white transition">Cancel</a>
                    <button type="submit"
                        class="px-8 py-3 rounded-xl bg-gradient-to-r from-[#6B4E71] to-[#8D6595] text-white font-semibold shadow-md hover:opacity-90 transition">Update
                        Event</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const input = document.getElementById('imageInput');
        const preview = document.getElementById('imagePreview');
        const placeholder = document.getElementById('uploadPlaceholder');
        input.addEventListener('change', function(event) {
            const [file] = event.target.files;
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('hidden');
                preview.classList.add('block');
                placeholder.classList.add('hidden');
            } else {
                preview.src = '#';
                preview.classList.add('hidden');
                preview.classList.remove('block');
                placeholder.classList.remove('hidden');
            }
        });
    </script>
@endpush
