@extends('layouts.admin')

@section('content')
    @if ($errors->any())
        <div class="bg-red-100 text-red-600 p-4 mb-4 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-xl mx-auto  my-8">
        <h2 class="text-2xl font-bold mb-4">Add New Event</h2>

        <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block">Title</label>
                <input type="text" name="title" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block">Date</label>
                <input type="date" name="date" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block">Time</label>
                <input type="time" name="time" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block">Type</label>
                <select name="type" class="w-full border rounded p-2" required>
                    <option value="concert">Koncert</option>
                    <option value="sport">Sport</option>
                    <option value="standup">Stand-up</option>
                    <option value="festival">Festival</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block">Description</label>
                <textarea name="description" class="w-full border rounded p-2" required></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Image Upload</label>
                <input type="file" name="image" accept="image/*"
                    class="block w-full text-sm text-gray-900 border
                    border-gray-300 rounded-lg cursor-pointer bg-[#FFEBFA]
                    focus:outline-none focus:ring-2 focus:ring-[#6B4E71]
                    focus:border-[#6B4E71] p-2.5 file:mr-4 file:py-2 file:px-4 f
                    ile:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#6B4E71]
                    file:text-white hover:file:bg-[#8c6694]s transition"
                    id="imageInput">
                <div class="mt-4">
                    <img id="imagePreview" src="#" alt="Image Preview"
                        class="hidden w-full h-48 object-cover rounded-md border" />
                </div>
            </div>

            @push('scripts')
                <script>
                    document.getElementById('imageInput').addEventListener('change', function(event) {
                        const [file] = event.target.files;
                        const preview = document.getElementById('imagePreview');
                        if (file) {
                            preview.src = URL.createObjectURL(file);
                            preview.classList.remove('hidden');
                        } else {
                            preview.src = '#';
                            preview.classList.add('hidden');
                        }
                    });
                </script>
            @endpush

            <div class="mb-4">
                <label class="block">Total Tickets</label>
                <input type="number" name="totalTickets" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block">Tickets Sold</label>
                <input type="number" name="ticketSold" class="w-full border rounded p-2" value="0">
            </div>
            <div class="mb-4">
                <label class="block">Organizer</label>
                <input type="text" name="organizer" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-4">
                <label class="block">Location</label>
                <input type="text" name="location" class="w-full border rounded p-2" required>
            </div>
            <div class="flex flex-row gap-4">
                <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded">Save Event</button>
                <a href="{{ route('events.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded">Cancel</a>
            </div>
        </form>
    </div>
@endsection
