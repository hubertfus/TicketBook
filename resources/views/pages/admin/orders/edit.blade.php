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
                @svg('heroicon-o-pencil-square', 'w-6 h-6 text-[#6B4E71]') Edit Order
            </h2>

            <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- User --}}
                <div>
                    <label class="block text-sm font-medium text-[#3A4454] mb-2">
                        @svg('heroicon-o-user', 'w-4 h-4 inline mr-1 text-[#6B4E71]') User
                    </label>
                    <select name="user_id" required
                        class="w-full px-4 py-3 bg-white rounded-xl shadow-inner focus:outline-none focus:ring-2 focus:ring-[#6B4E71]">
                        @foreach (\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}"
                                {{ $user->id == old('user_id', $order->user_id) ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
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
                            <option value="{{ $status }}"
                                {{ $status == old('status', $order->status) ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tickets (Order Items) --}}
                <div id="ticket-items" class="space-y-4">
                    @foreach (old('tickets', $order->orderItems->map(
                        fn($item) => [
                            'ticket_id' => $item->ticket_id,
                            'quantity' => $item->quantity,
                            'unit_price' => $item->unit_price,
                        ],
                    )->toArray()) as $index => $ticketItem)
                        <div class="ticket-item relative bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                                <div>
                                    <label class="block text-sm font-medium text-[#3A4454] mb-2">Ticket</label>
                                    <select name="tickets[{{ $index }}][ticket_id]"
                                        class="ticket-select w-full px-4 py-3 bg-white rounded-xl shadow-inner" required>
                                        @foreach ($tickets as $ticket)
                                            <option value="{{ $ticket->id }}"
                                                data-price="{{ $ticket->price }}"
                                                data-event-id="{{ $ticket->event_id }}"
                                                {{ $ticket->id == $ticketItem['ticket_id'] ? 'selected' : '' }}>
                                                {{ $ticket->category }} – {{ $ticket->event->title }}
                                                ({{ number_format($ticket->price, 2) }} PLN)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#3A4454] mb-2">Quantity</label>
                                    <input type="number" name="tickets[{{ $index }}][quantity]" min="1"
                                        max="10" value="{{ $ticketItem['quantity'] }}"
                                        class="ticket-quantity w-full px-4 py-3 bg-white rounded-xl shadow-inner"
                                        required />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#3A4454] mb-2">Unit Price (PLN)</label>
                                    <input type="number" name="tickets[{{ $index }}][unit_price]" step="0.01"
                                        value="{{ $ticketItem['unit_price'] }}"
                                        class="ticket-price w-full px-4 py-3 bg-white rounded-xl shadow-inner" required />
                                </div>
                            </div>
                            @if($index > 0)
                                <button type="button" class="remove-ticket absolute top-2 right-2 text-red-500 hover:text-red-700">
                                    @svg('heroicon-o-x-mark', 'w-5 h-5')
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>

                <button type="button" id="add-ticket-btn" class="px-4 py-2 bg-[#6B4E71] text-white rounded-lg hover:bg-[#8D6595] transition">
                    @svg('heroicon-o-plus', 'w-4 h-4 inline mr-1') Add Another Ticket
                </button>

                <div class="mt-6 text-right text-xl font-semibold text-[#6B4E71]">
                    Total: <span id="total-amount">0.00</span> PLN
                </div>

                {{-- Submit --}}
                <div class="pt-6 border-t border-[#6B4E71]/20 flex justify-end gap-4">
                    <a href="{{ route('admin.orders.index') }}"
                        class="px-6 py-3 rounded-xl bg-transparent border border-[#6B4E71] text-[#6B4E71] hover:bg-[#6B4E71] hover:text-white transition">Cancel</a>
                    <button type="submit"
                        class="px-8 py-3 rounded-xl bg-gradient-to-r from-[#6B4E71] to-[#8D6595] text-white font-semibold shadow-md hover:opacity-90 transition">Save
                        Changes</button>
                </div>
            </form>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        let ticketIndex = {{ count(old('tickets', $order->orderItems)) }};
        let currentEventId = {{ $order->orderItems->first()->ticket->event_id ?? 'null' }};

        const allTickets = {!! json_encode($tickets->map(function($ticket) {
            return [
                'id' => $ticket->id,
                'event_id' => $ticket->event_id,
                'price' => $ticket->price,
                'text' => $ticket->category . ' – ' . $ticket->event->title . ' (' . number_format($ticket->price, 2) . ' PLN)'
            ];
        })) !!};

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('.ticket-item').forEach(item => {
                const qty = parseFloat(item.querySelector('.ticket-quantity').value) || 0;
                const price = parseFloat(item.querySelector('.ticket-price').value) || 0;
                total += qty * price;
            });
            document.getElementById('total-amount').textContent = total.toFixed(2);
        }

        function setPriceFromSelect(select) {
            const selected = select.options[select.selectedIndex];
            if (!selected) return;
            const price = selected.getAttribute('data-price');
            const eventId = selected.getAttribute('data-event-id');
            const container = select.closest('.ticket-item');
            container.querySelector('.ticket-price').value = price;

            const isFirstTicket = container === document.querySelector('.ticket-item');
            if (isFirstTicket && currentEventId !== eventId) {
                currentEventId = eventId;
                updateOtherTicketsEvent();
            }

            updateTotal();
        }

        function updateOtherTicketsEvent() {
            const ticketItems = document.querySelectorAll('.ticket-item');
            if (ticketItems.length <= 1) return;

            const availableTickets = allTickets.filter(t => t.event_id == currentEventId);
            if (availableTickets.length === 0) return;

            for (let i = 1; i < ticketItems.length; i++) {
                const select = ticketItems[i].querySelector('.ticket-select');
                const currentValue = select.value;

                select.innerHTML = '';
                availableTickets.forEach(ticket => {
                    const option = document.createElement('option');
                    option.value = ticket.id;
                    option.textContent = ticket.text;
                    option.setAttribute('data-price', ticket.price);
                    option.setAttribute('data-event-id', ticket.event_id);
                    select.appendChild(option);
                });

                const ticketExists = availableTickets.some(t => t.id == currentValue);
                if (ticketExists) {
                    select.value = currentValue;
                } else {
                    select.value = availableTickets[0].id;
                }

                setPriceFromSelect(select);
            }

            updateAddButtonState();
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

        function getAvailableTickets() {
            const selectedTicketIds = getSelectedTicketIds();
            return allTickets.filter(ticket =>
                ticket.event_id == currentEventId &&
                !selectedTicketIds.includes(ticket.id.toString())
            );
        }

        function updateAddButtonState() {
            const addButton = document.getElementById('add-ticket-btn');
            if (!currentEventId) {
                addButton.disabled = true;
                addButton.title = "Please select a ticket type first";
                return;
            }

            const availableTickets = getAvailableTickets();
            addButton.disabled = availableTickets.length === 0;

            if (availableTickets.length === 0) {
                addButton.title = "All ticket types for this event have been added";
            } else {
                addButton.title = "";
            }
        }

        function validateAll() {
            if (hasDuplicateTicketIds()) {
                alert("You can't select the same ticket more than once.");
                return false;
            }

            const allEventIds = Array.from(document.querySelectorAll('.ticket-select'))
                .map(select => select.options[select.selectedIndex]?.getAttribute('data-event-id'))
                .filter(id => id);

            const uniqueEventIds = [...new Set(allEventIds)];
            if (uniqueEventIds.length > 1) {
                alert("All tickets must be from the same event.");
                return false;
            }

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
                item.querySelector('.ticket-select').setAttribute('name', `tickets[${index}][ticket_id]`);
                item.querySelector('.ticket-quantity').setAttribute('name', `tickets[${index}][quantity]`);
                item.querySelector('.ticket-price').setAttribute('name', `tickets[${index}][unit_price]`);
            });
        }

        function removeTicket(button) {
            const ticketItems = document.querySelectorAll('.ticket-item');
            if (ticketItems.length <= 1) {
                alert('You must have at least one ticket in the order.');
                return;
            }

            const ticketItem = button.closest('.ticket-item');
            ticketItem.remove();
            updateTicketIndexes();
            updateTotal();
            updateAddButtonState();
        }

        function addTicketItem() {
            const availableTickets = getAvailableTickets();
            if (availableTickets.length === 0) return alert("All ticket types for this event have been added");

            const ticket = availableTickets[0];
            const container = document.getElementById('ticket-items');
            const div = document.createElement('div');
            div.classList.add('ticket-item', 'relative', 'bg-white', 'p-4', 'rounded-xl', 'shadow-sm', 'border', 'border-gray-200');
            div.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-[#3A4454] mb-2">Ticket</label>
                        <select name="tickets[${ticketIndex}][ticket_id]" class="ticket-select w-full px-4 py-3 bg-white rounded-xl shadow-inner" required>
                            ${allTickets
                                .filter(t => t.event_id == currentEventId)
                                .filter(t => !getSelectedTicketIds().includes(t.id.toString()))
                                .map(t => `<option value="${t.id}" data-price="${t.price}" data-event-id="${t.event_id}" ${t.id == ticket.id ? 'selected' : ''}>${t.text}</option>`)
                                .join('')}
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#3A4454] mb-2">Quantity</label>
                        <input type="number" name="tickets[${ticketIndex}][quantity]" min="1" max="10" value="1" class="ticket-quantity w-full px-4 py-3 bg-white rounded-xl shadow-inner" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#3A4454] mb-2">Unit Price (PLN)</label>
                        <input type="number" name="tickets[${ticketIndex}][unit_price]" step="0.01" value="${ticket.price}" class="ticket-price w-full px-4 py-3 bg-white rounded-xl shadow-inner" required />
                    </div>
                </div>
                <button type="button" class="remove-ticket absolute top-2 right-2 text-red-500 hover:text-red-700">
                    @svg('heroicon-o-x-mark', 'w-5 h-5')
                </button>
            `;

            container.appendChild(div);

            div.querySelector('.ticket-select').addEventListener('change', (e) => {
                setPriceFromSelect(e.target);
                if (hasDuplicateTicketIds()) {
                    alert("You can't select the same ticket more than once.");
                    e.target.value = '';
                }
                updateAddButtonState();
            });

            div.querySelector('.ticket-quantity').addEventListener('input', updateTotal);
            div.querySelector('.ticket-price').addEventListener('input', updateTotal);
            div.querySelector('.remove-ticket').addEventListener('click', (e) => {
                removeTicket(e.target);
            });

            ticketIndex++;
            updateAddButtonState();
            updateTotal();
        }

        document.querySelectorAll('.ticket-select').forEach(select => {
            select.addEventListener('change', (e) => {
                setPriceFromSelect(e.target);
                if (hasDuplicateTicketIds()) {
                    alert("You can't select the same ticket more than once.");
                    e.target.value = '';
                }
                updateAddButtonState();
            });
            setPriceFromSelect(select);
        });

        document.querySelectorAll('.ticket-quantity').forEach(input => {
            input.addEventListener('input', updateTotal);
        });

        document.querySelectorAll('.ticket-price').forEach(input => {
            input.addEventListener('input', updateTotal);
        });

        document.querySelectorAll('.remove-ticket').forEach(btn => {
            btn.addEventListener('click', (e) => {
                removeTicket(e.target);
            });
        });

        document.getElementById('add-ticket-btn').addEventListener('click', () => {
            addTicketItem();
        });

        document.querySelector('form').addEventListener('submit', (e) => {
            if (!validateAll()) {
                e.preventDefault();
            }
        });

        updateAddButtonState();
        updateTotal();
    });
</script>
@endsection
