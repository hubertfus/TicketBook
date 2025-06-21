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

            <form action="{{ route('admin.orders.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- User selection --}}
                <div>
                    <label class="block text-sm font-medium text-[#3A4454] mb-2">
                        @svg('heroicon-o-user', 'w-4 h-4 inline mr-1 text-[#6B4E71]') User
                    </label>
                    <select name="user_id" required
                        class="w-full px-4 py-3 bg-white rounded-xl shadow-inner focus:outline-none focus:ring-2 focus:ring-[#6B4E71]">
                        @foreach (\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>{{ $user->name }} ({{ $user->email }})</option>
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
                            <option value="{{ $status }}" @selected(old('status', 'pending') == $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Ticket Items --}}
                <div id="ticket-items" class="space-y-4">
                    @php
                        $oldTickets = old('tickets', [
                            ['ticket_id' => $tickets->first()->id ?? '', 'quantity' => 1, 'unit_price' => $tickets->first()->price ?? 0]
                        ]);
                    @endphp

                    @foreach ($oldTickets as $index => $oldTicket)
                        <div class="ticket-item relative bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                                <div>
                                    <label class="block text-sm font-medium text-[#3A4454] mb-2">Ticket</label>
                                    <select name="tickets[{{ $index }}][ticket_id]"
                                        class="ticket-select w-full px-4 py-3 bg-white rounded-xl shadow-inner" required>
                                        @foreach ($tickets as $ticket)
                                            <option value="{{ $ticket->id }}" data-price="{{ $ticket->price }}" data-event-id="{{ $ticket->event_id }}"
                                                @selected($oldTicket['ticket_id'] == $ticket->id)>
                                                {{ $ticket->category }} – {{ $ticket->event->title }} ({{ number_format($ticket->price, 2) }} PLN)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#3A4454] mb-2">Quantity</label>
                                    <input type="number" name="tickets[{{ $index }}][quantity]" min="1" max="10"
                                        value="{{ $oldTicket['quantity'] ?? 1 }}" class="ticket-quantity w-full px-4 py-3 bg-white rounded-xl shadow-inner" required />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#3A4454] mb-2">Unit Price (PLN)</label>
                                    <input type="number" name="tickets[{{ $index }}][unit_price]" step="0.01"
                                        value="{{ $oldTicket['unit_price'] ?? 0 }}" class="ticket-price w-full px-4 py-3 bg-white rounded-xl shadow-inner" required />
                                </div>
                            </div>
                            @if ($index > 0)
                                <button type="button" class="remove-ticket absolute top-2 right-2 text-red-500 hover:text-red-700">
                                    @svg('heroicon-o-x-mark', 'w-5 h-5')
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>

                <button type="button" id="add-ticket-btn"
                    class="px-4 py-2 bg-[#6B4E71] text-white rounded-lg hover:bg-[#8D6595] transition" disabled title="Select a ticket first">
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
                        class="px-8 py-3 rounded-xl bg-gradient-to-r from-[#6B4E71] to-[#8D6595] text-white font-semibold shadow-md hover:opacity-90 transition">Create
                        Order</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        let ticketIndex = {{ count($oldTickets) }};
        let currentEventId = null;

        const allTickets = Array.from(document.querySelectorAll('.ticket-select option')).map(opt => ({
            id: opt.value,
            eventId: opt.getAttribute('data-event-id'),
            price: opt.getAttribute('data-price'),
            text: opt.textContent.trim()
        }));

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('.ticket-item').forEach(item => {
                const qty = parseFloat(item.querySelector('.ticket-quantity').value) || 0;
                const price = parseFloat(item.querySelector('.ticket-price').value) || 0;
                total += qty * price;
            });
            document.getElementById('total-amount').textContent = total.toFixed(2);
        }

        function getSelectedTicketIds() {
            return Array.from(document.querySelectorAll('.ticket-select')).map(sel => sel.value).filter(v => v);
        }

        function getSelectedEventIds() {
            return Array.from(document.querySelectorAll('.ticket-select')).map(sel => sel.options[sel.selectedIndex]?.getAttribute('data-event-id')).filter(v => v);
        }

        function hasMultipleEvents() {
            return new Set(getSelectedEventIds()).size > 1;
        }

        function setCurrentEvent() {
            const eventIds = getSelectedEventIds();
            const unique = [...new Set(eventIds)];
            currentEventId = unique.length === 1 ? unique[0] : null;
        }

        function updateTicketIndexes() {
            document.querySelectorAll('.ticket-item').forEach((item, index) => {
                item.querySelector('.ticket-select').setAttribute('name', `tickets[${index}][ticket_id]`);
                item.querySelector('.ticket-quantity').setAttribute('name', `tickets[${index}][quantity]`);
                item.querySelector('.ticket-price').setAttribute('name', `tickets[${index}][unit_price]`);
            });
        }

        function updateSelectOptions() {
            const selectedIds = getSelectedTicketIds();
            const selects = document.querySelectorAll('.ticket-select');
            const isFirstSelect = (select) => select.closest('.ticket-item') === document.querySelector('.ticket-item');
            const hasMultipleTickets = selects.length > 1;

            selects.forEach(currentSelect => {
                const currentValue = currentSelect.value;
                const isFirst = isFirstSelect(currentSelect);

                Array.from(currentSelect.options).forEach(opt => {
                    const ticketId = opt.value;
                    const eventId = opt.getAttribute('data-event-id');

                    if (isFirst) {
                        const isSelectedElsewhere = selectedIds.includes(ticketId) && ticketId !== currentValue;
                        const isDifferentEvent = hasMultipleTickets && currentEventId && eventId !== currentEventId;

                        opt.disabled = isSelectedElsewhere || isDifferentEvent;
                        opt.style.display = isSelectedElsewhere ? 'none' : '';
                    } else {
                        const isSelectedElsewhere = selectedIds.includes(ticketId) && ticketId !== currentValue;
                        const isDifferentEvent = currentEventId && eventId !== currentEventId;

                        opt.disabled = isSelectedElsewhere || isDifferentEvent;
                        opt.style.display = isSelectedElsewhere ? 'none' : '';
                    }
                });
            });
        }

        function updateAddButtonState() {
            const btn = document.getElementById('add-ticket-btn');
            const selectedIds = getSelectedTicketIds();
            const available = allTickets.filter(t =>
                t.eventId === currentEventId &&
                !selectedIds.includes(t.id)
            );

            btn.disabled = !currentEventId || available.length === 0;
            btn.title = !currentEventId
                ? 'Select a ticket type to enable'
                : available.length === 0
                    ? 'No more ticket types available for this event.'
                    : '';
        }

        function onTicketChange(e) {
            const sel = e.target;
            const isFirstSelect = sel.closest('.ticket-item') === document.querySelector('.ticket-item');
            const hasMultipleTickets = document.querySelectorAll('.ticket-select').length > 1;

            if (isFirstSelect && sel.value && !hasMultipleTickets) {
                const selectedOption = sel.options[sel.selectedIndex];
                const newEventId = selectedOption.getAttribute('data-event-id');
                currentEventId = newEventId;
            } else if (isFirstSelect && hasMultipleTickets) {
                const selectedOption = sel.options[sel.selectedIndex];
                const newEventId = selectedOption.getAttribute('data-event-id');
                if (currentEventId && newEventId !== currentEventId) {
                    alert('Cannot change event when other tickets are already selected.');
                    sel.value = sel.querySelector(`option[data-event-id="${currentEventId}"]`)?.value || '';
                    return;
                }
                currentEventId = newEventId;
            } else if (!isFirstSelect && hasMultipleEvents()) {
                alert('All tickets must belong to the same event.');
                sel.value = '';
                return;
            }

            updateSelectOptions();
            setPriceFromSelect(sel);
            updateAddButtonState();
            updateTotal();
        }

        function setPriceFromSelect(select) {
            const opt = select.options[select.selectedIndex];
            if (!opt) return;
            select.closest('.ticket-item').querySelector('.ticket-price').value = opt.getAttribute('data-price');
        }

        function removeTicket(button) {
            const ticketItem = button.closest('.ticket-item');
            const isFirstItem = ticketItem === document.querySelector('.ticket-item');

            if (isFirstItem) {
                alert('Cannot remove the first ticket item.');
                return;
            }

            ticketItem.remove();
            updateTicketIndexes();
            setCurrentEvent();
            updateSelectOptions();
            updateAddButtonState();
            updateTotal();
        }

        function addTicketItem() {
            if (!currentEventId) return alert('Select a ticket type first.');

            const selectedIds = getSelectedTicketIds();
            const available = allTickets.filter(t =>
                t.eventId === currentEventId &&
                !selectedIds.includes(t.id)
            );

            if (available.length === 0) return alert('No more ticket types available for this event.');

            const ticket = available[0];
            const container = document.getElementById('ticket-items');
            const div = document.createElement('div');
            div.classList.add('ticket-item', 'relative', 'bg-white', 'p-4', 'rounded-xl', 'shadow-sm', 'border', 'border-gray-200');
            div.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-[#3A4454] mb-2">Ticket</label>
                        <select name="tickets[${ticketIndex}][ticket_id]" class="ticket-select w-full px-4 py-3 bg-white rounded-xl shadow-inner" required>
                            <option value="">Select a ticket...</option>
                            ${allTickets
                                .filter(t => t.eventId === currentEventId && !selectedIds.includes(t.id))
                                .map(t => `<option value="${t.id}" data-price="${t.price}" data-event-id="${t.eventId}">${t.text}</option>`)
                                .join('')}
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#3A4454] mb-2">Quantity</label>
                        <input type="number" name="tickets[${ticketIndex}][quantity]" min="1" max="10" value="1" class="ticket-quantity w-full px-4 py-3 bg-white rounded-xl shadow-inner" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#3A4454] mb-2">Unit Price (PLN)</label>
                        <input type="number" name="tickets[${ticketIndex}][unit_price]" step="0.01" value="0" class="ticket-price w-full px-4 py-3 bg-white rounded-xl shadow-inner" required />
                    </div>
                </div>
                <button type="button" class="remove-ticket absolute top-2 right-2 text-red-500 hover:text-red-700">
                    @svg('heroicon-o-x-mark', 'w-5 h-5')
                </button>
            `;

            container.appendChild(div);

            const newSelect = div.querySelector('.ticket-select');
            const newQty = div.querySelector('.ticket-quantity');
            const newPrice = div.querySelector('.ticket-price');
            const newRemove = div.querySelector('.remove-ticket');

            newSelect.addEventListener('change', onTicketChange);
            newQty.addEventListener('input', updateTotal);
            newPrice.addEventListener('input', updateTotal);
            newRemove.addEventListener('click', e => removeTicket(e.target));

            ticketIndex++;
            updateTicketIndexes();
            updateSelectOptions();
            updateAddButtonState();
            updateTotal();
        }

        document.querySelectorAll('.ticket-select').forEach(sel => sel.addEventListener('change', onTicketChange));
        document.querySelectorAll('.ticket-quantity').forEach(inp => inp.addEventListener('input', updateTotal));
        document.querySelectorAll('.ticket-price').forEach(inp => inp.addEventListener('input', updateTotal));
        document.querySelectorAll('.remove-ticket').forEach(btn => btn.addEventListener('click', e => removeTicket(e.target)));

        document.getElementById('add-ticket-btn').addEventListener('click', addTicketItem);

        const firstSelect = document.querySelector('.ticket-select');
        if (firstSelect && firstSelect.value) {
            const firstOption = firstSelect.options[firstSelect.selectedIndex];
            currentEventId = firstOption.getAttribute('data-event-id');
        } else {
            setCurrentEvent();
        }

        updateSelectOptions();
        updateAddButtonState();
        updateTotal();
    });
</script>
@endsection
