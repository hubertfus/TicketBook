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
        <div class="max-w-4xl mx-auto bg-[#FFEBFA] rounded-2xl shadow-lg p-8">
            <h2 class="text-3xl font-extrabold text-[#3A4454] flex items-center gap-2 mb-6">
                @svg('heroicon-o-plus-circle', 'w-6 h-6 text-[#6B4E71]') Add New Order
            </h2>

            <form action="{{ route('orders.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- User selection --}}
                <div>
                    <label class="block text-sm font-medium text-[#3A4454] mb-2">
                        @svg('heroicon-o-user', 'w-4 h-4 inline mr-1 text-[#6B4E71]') User
                    </label>
                    <select name="user_id" required
                        class="w-full px-4 py-3 bg-white rounded-xl shadow-inner focus:outline-none focus:ring-2 focus:ring-[#6B4E71]">
                        @foreach (\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-medium text-[#3A4454] mb-2">
                        @svg('heroicon-o-flag', 'w-4 h-4 inline mr-1 text-[#6B4E71]') Status
                    </label>
                    <select name="status" required
                        class="w-full px-4 py-3 bg-white rounded-xl shadow-inner focus:outline-none focus:ring-2 focus:ring-[#6B4E71]">
                        @foreach (['pending', 'paid', 'cancelled', 'refunded'] as $status)
                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Ticket Items --}}
                <div id="ticket-items" class="space-y-4">
                    <div class="ticket-item relative bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div>
                                <label class="block text-sm font-medium text-[#3A4454] mb-2">Ticket</label>
                                <select name="tickets[0][ticket_id]" class="ticket-select w-full px-4 py-3 bg-white rounded-xl shadow-inner"
                                    required>
                                    @foreach ($tickets as $ticket)
                                        <option value="{{ $ticket->id }}" data-price="{{ $ticket->price }}">
                                            {{ $ticket->category }} – {{ $ticket->event->title }} ({{ number_format($ticket->price, 2) }} PLN)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[#3A4454] mb-2">Quantity</label>
                                <input type="number" name="tickets[0][quantity]" min="1" max="10" value="1"
                                    class="ticket-quantity w-full px-4 py-3 bg-white rounded-xl shadow-inner" required />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[#3A4454] mb-2">Unit Price (PLN)</label>
                                <input type="number" name="tickets[0][unit_price]" step="0.01"
                                    class="ticket-price w-full px-4 py-3 bg-white rounded-xl shadow-inner" required />
                            </div>
                        </div>

                        {{-- Remove button (hidden for first item initially) --}}
                        <button type="button" class="remove-ticket absolute top-2 right-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-full p-2 transition-colors duration-200 hidden" title="Remove this ticket">
                            @svg('heroicon-o-trash', 'w-5 h-5')
                        </button>
                    </div>
                </div>

                <div class="flex justify-between mt-4">
                    <button type="button" id="add-ticket"
                        class="text-sm text-[#6B4E71] font-medium hover:underline flex items-center gap-1">
                        @svg('heroicon-o-plus-circle', 'w-4 h-4') Add Another Ticket
                    </button>
                    <div class="text-lg font-semibold text-[#3A4454]">
                        Total: <span id="total-amount">0.00</span> PLN
                    </div>
                </div>

                {{-- Submit --}}
                <div class="pt-6 border-t border-[#6B4E71]/20 flex justify-end gap-4">
                    <a href="{{ route('orders.index') }}"
                        class="px-6 py-3 rounded-xl bg-transparent border border-[#6B4E71] text-[#6B4E71] hover:bg-[#6B4E71] hover:text-white transition">Cancel</a>
                    <button type="submit"
                        class="px-8 py-3 rounded-xl bg-gradient-to-r from-[#6B4E71] to-[#8D6595] text-white font-semibold shadow-md hover:opacity-90 transition">Create Order</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        let ticketIndex = 1;

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('.ticket-item').forEach(item => {
                const qty = item.querySelector('.ticket-quantity').value;
                const price = item.querySelector('.ticket-price').value;
                total += parseFloat(qty || 0) * parseFloat(price || 0);
            });
            document.getElementById('total-amount').textContent = total.toFixed(2);
        }

        function setPriceFromSelect(select) {
            const selected = select.options[select.selectedIndex];
            const price = selected.getAttribute('data-price');
            const container = select.closest('.ticket-item');
            container.querySelector('.ticket-price').value = price;
            updateTotal();
        }

        function getSelectedTicketIds() {
            return Array.from(document.querySelectorAll('.ticket-select'))
                .map(select => select.value)
                .filter(id => id !== '');
        }

        function hasDuplicateTicketIds() {
            const ids = getSelectedTicketIds();
            return new Set(ids).size !== ids.length;
        }

        function validateAll() {
            // Duplicate check
            if (hasDuplicateTicketIds()) {
                alert("You can't select the same ticket more than once.");
                return false;
            }

            // Quantity check
            let valid = true;
            document.querySelectorAll('.ticket-quantity').forEach(input => {
                const val = parseInt(input.value);
                if (val > 10) {
                    alert("Maximum quantity per ticket is 10.");
                    input.value = 10;
                    valid = false;
                }
            });

            return valid;
        }

        function updateTicketIndexes() {
            document.querySelectorAll('.ticket-item').forEach((item, index) => {
                const select = item.querySelector('.ticket-select');
                const quantityInput = item.querySelector('.ticket-quantity');
                const priceInput = item.querySelector('.ticket-price');

                select.setAttribute('name', `tickets[${index}][ticket_id]`);
                quantityInput.setAttribute('name', `tickets[${index}][quantity]`);
                priceInput.setAttribute('name', `tickets[${index}][unit_price]`);
            });
        }

        function updateRemoveButtonsVisibility() {
            const ticketItems = document.querySelectorAll('.ticket-item');
            const showRemoveButtons = ticketItems.length > 1;

            ticketItems.forEach(item => {
                const removeButton = item.querySelector('.remove-ticket');
                if (showRemoveButtons) {
                    removeButton.classList.remove('hidden');
                } else {
                    removeButton.classList.add('hidden');
                }
            });
        }

        function removeTicket(button) {
            const ticketItems = document.querySelectorAll('.ticket-item');

            if (ticketItems.length <= 1) {
                alert('You must have at least one ticket in the order.');
                return;
            }

            if (confirm('Are you sure you want to remove this ticket?')) {
                const ticketItem = button.closest('.ticket-item');
                ticketItem.remove();
                updateTicketIndexes();
                updateRemoveButtonsVisibility();
                updateTotal();
            }
        }

        // Initialize first ticket
        document.querySelectorAll('.ticket-select').forEach(select => {
            select.addEventListener('change', () => {
                setPriceFromSelect(select);
                validateAll();
            });
            setPriceFromSelect(select);
        });

        // Initialize remove button for first ticket
        document.querySelectorAll('.remove-ticket').forEach(button => {
            button.addEventListener('click', () => removeTicket(button));
        });

        document.getElementById('ticket-items').addEventListener('input', () => {
            updateTotal();
            validateAll();
        });

        document.getElementById('add-ticket').addEventListener('click', () => {
            if (!validateAll()) return;

            if (ticketIndex >= 10) {
                alert('You can add up to 10 ticket types only.');
                return;
            }

            if (hasDuplicateTicketIds()) {
                alert("You can't select the same ticket more than once.");
                return;
            }

            const container = document.getElementById('ticket-items');
            const div = document.createElement('div');
            div.className = 'ticket-item relative bg-white p-4 rounded-xl shadow-sm border border-gray-200';

            div.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-[#3A4454] mb-2">Ticket</label>
                        <select name="tickets[${ticketIndex}][ticket_id]" class="ticket-select w-full px-4 py-3 bg-white rounded-xl shadow-inner" required>
                            @foreach ($tickets as $ticket)
                                <option value="{{ $ticket->id }}" data-price="{{ $ticket->price }}">
                                    {{ $ticket->category }} – {{ $ticket->event->title }} ({{ number_format($ticket->price, 2) }} PLN)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#3A4454] mb-2">Quantity</label>
                        <input type="number" name="tickets[${ticketIndex}][quantity]" min="1" max="10" value="1" class="ticket-quantity w-full px-4 py-3 bg-white rounded-xl shadow-inner" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#3A4454] mb-2">Unit Price (PLN)</label>
                        <input type="number" name="tickets[${ticketIndex}][unit_price]" step="0.01" class="ticket-price w-full px-4 py-3 bg-white rounded-xl shadow-inner" required />
                    </div>
                </div>

                <button type="button" class="remove-ticket absolute top-2 right-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-full p-2 transition-colors duration-200" title="Remove this ticket">
                    @svg('heroicon-o-trash', 'w-5 h-5')
                </button>
            `;

            container.appendChild(div);

            const newSelect = div.querySelector('.ticket-select');
            const newRemoveButton = div.querySelector('.remove-ticket');

            newSelect.addEventListener('change', () => {
                setPriceFromSelect(newSelect);
                validateAll();
            });

            newRemoveButton.addEventListener('click', () => removeTicket(newRemoveButton));

            div.querySelector('.ticket-quantity').addEventListener('input', validateAll);

            setPriceFromSelect(newSelect);
            ticketIndex++;
            updateRemoveButtonsVisibility();
            updateTotal();
        });

        // Final check before submission
        document.querySelector('form').addEventListener('submit', function (e) {
            if (!validateAll()) {
                e.preventDefault();
            }
        });

        updateTotal();
        updateRemoveButtonsVisibility();
    });
</script>

@endsection
