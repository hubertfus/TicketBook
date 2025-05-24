@extends('layouts.admin')

@section('content')
    <div class="max-w-xl mx-auto my-8">
        <h2 class="text-2xl font-bold mb-4">Edit Event</h2>

        <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block">Title</label>
                <input type="text" name="title" value="{{ $event->title }}" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block">Date</label>
                <input type="date" name="date" value="{{ \Carbon\Carbon::parse($event->date)->format('Y-m-d') }}"
                    class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block">Time</label>
                <input type="time" name="time" value="{{ \Carbon\Carbon::parse($event->time)->format('H:i') }}"
                    class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block">Type</label>
                <input type="text" name="type" value="{{ $event->type }}" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block">Description</label>
                <textarea name="description" class="w-full border rounded p-2" required>{{ $event->description }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-700">Image Upload</label>
                <input type="file" name="image" id="imageInput" class="w-full border rounded p-2" accept="image/*">
                <div class="mt-4">
                    <img id="imagePreview" src="{{ $event->image ? asset('storage/' . $event->image) : '#' }}"
                        alt="Image Preview"
                        class="{{ $event->image ? 'block' : 'hidden' }} w-full h-48 object-cover rounded-md border" />
                </div>
            </div>

            <div class="mb-4">
                <label class="block">Total Tickets</label>
                <input type="number" name="totalTickets" value="{{ $event->totalTickets }}"
                    class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block">Tickets Sold</label>
                <input type="number" name="ticketSold" value="{{ $event->ticketSold }}" class="w-full border rounded p-2">
            </div>

            <div class="mb-4">
                <label class="block">Organizer</label>
                <input type="text" name="organizer" value="{{ $event->organizer }}" class="w-full border rounded p-2"
                    required>
            </div>

            <div class="mb-4">
                <label class="block">Location</label>
                <input type="text" name="location" value="{{ $event->location }}" class="w-full border rounded p-2"
                    required>
            </div>

            <div class="flex flex-row gap-4">
                <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded">Update Event</button>
                <a href="{{ route('events.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded">Cancel</a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('imageInput').addEventListener('change', function(event) {
            const [file] = event.target.files;
            const preview = document.getElementById('imagePreview');
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('hidden');
                preview.classList.add('block');
            } else {
                preview.src = '#';
                preview.classList.remove('block');
                preview.classList.add('hidden');
            }
        });
    </script>
@endpush
