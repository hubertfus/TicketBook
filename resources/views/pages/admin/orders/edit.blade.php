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

    @php
        $ticketsData = old(
            'tickets',
            $order->orderItems->map(fn($item) => [
                'ticket_id'   => $item->ticket_id,
                'quantity'    => $item->quantity,
                'unit_price'  => $item->unit_price,
            ])->toArray()
        );

        $firstTicket = $tickets->firstWhere('id', $ticketsData[0]['ticket_id'] ?? null);
        $currentEventId = $firstTicket->event_id ?? null;

        $filteredTickets = $tickets->where('event_id', $currentEventId)->values()->map(fn($t) => [
            'id'    => $t->id,
            'label' => $t->category . ' – ' . $t->event->title . ' (' . number_format($t->price,2) . ' PLN)',
            'price' => $t->price,
            'event_id' => $t->event_id,
        ])->toArray();

        $allTicketsJs = $tickets->map(fn($t) => [
            'id'       => $t->id,
            'label'    => $t->category . ' – ' . $t->event->title . ' (' . number_format($t->price,2) . ' PLN)',
            'price'    => $t->price,
            'event_id' => $t->event_id,
        ])->values()->toArray();
    @endphp

    <div class="min-h-screen bg-[#FFF7FD] py-10 px-4">
        <div class="max-w-4xl mx-auto bg-[#FFEBFA] rounded-2xl shadow-lg p-8">
            <h2 class="text-3xl font-extrabold text-[#3A4454] flex items-center gap-2 mb-6">
                @svg('heroicon-o-pencil-square','w-6 h-6 text-[#6B4E71]') Edit Order
            </h2>

            <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="space-y-6">
                @csrf @method('PUT')

                {{-- User --}}
                <div>
                    <label class="block text-sm font-medium text-[#3A4454] mb-2">User</label>
                    <select name="user_id" required class="w-full px-4 py-3 bg-white rounded-xl shadow-inner focus:ring-2">
                        @foreach(\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}" {{ $user->id==old('user_id',$order->user_id)?'selected':'' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-medium text-[#3A4454] mb-2">Status</label>
                    <select name="status" required class="w-full px-4 py-3 bg-white rounded-xl shadow-inner focus:ring-2">
                        @foreach(['pending','paid','cancelled','refunded'] as $status)
                            <option value="{{ $status }}" {{ $status==old('status',$order->status)?'selected':'' }}>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tickets --}}
                <div id="ticket-items" class="space-y-4">
                    @foreach($ticketsData as $i=>$item)
                        <div class="ticket-item relative bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Ticket</label>
                                    <select name="tickets[{{ $i }}][ticket_id]" class="ticket-select w-full px-4 py-3 bg-white rounded-xl shadow-inner" required>
                                        @foreach($filteredTickets as $t)
                                            <option value="{{ $t['id'] }}" data-price="{{ $t['price'] }}" data-event-id="{{ $t['event_id'] }}"
                                                {{ $t['id']==$item['ticket_id']?'selected':'' }}>{{ $t['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Quantity</label>
                                    <input type="number" name="tickets[{{ $i }}][quantity]" min="1" max="10" value="{{ $item['quantity'] }}" class="ticket-quantity w-full px-4 py-3 bg-white rounded-xl shadow-inner" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Unit Price</label>
                                    <input type="number" name="tickets[{{ $i }}][unit_price]" step="0.01" value="{{ $item['unit_price'] }}" class="ticket-price w-full px-4 py-3 bg-white rounded-xl shadow-inner" required>
                                </div>
                            </div>
                            @if($i>0)
                                <button type="button" class="remove-ticket absolute top-2 right-2 text-red-500 hover:text-red-700">
                                    @svg('heroicon-o-x-mark','w-5 h-5')
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>

                <button type="button" id="add-ticket-btn" class="px-4 py-2 bg-[#6B4E71] text-white rounded-lg hover:bg-[#8D6595] transition">
                    @svg('heroicon-o-plus','w-4 h-4 inline mr-1') Add Another Ticket
                </button>

                <div class="mt-6 text-right text-xl font-semibold text-[#6B4E71]">
                    Total: <span id="total-amount">0.00</span> PLN
                </div>

                <div class="pt-6 border-t border-[#6B4E71]/20 flex justify-end gap-4">
                    <a href="{{ route('admin.orders.index') }}" class="px-6 py-3 rounded-xl border border-[#6B4E71] text-[#6B4E71] hover:bg-[#6B4E71] hover:text-white">Cancel</a>
                    <button type="submit" class="px-8 py-3 rounded-xl bg-gradient-to-r from-[#6B4E71] to-[#8D6595] text-white font-semibold shadow-md hover:opacity-90">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        let ticketIndex = {{ count($ticketsData) }};
        let currentEventId = @json($currentEventId);

        const allTickets = Object.values(@json($allTicketsJs));
        const filteredTickets = @json($filteredTickets);

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
            return Array.from(document.querySelectorAll('.ticket-select')).map(sel => sel.selectedOptions[0]?.dataset.eventId).filter(v => v);
        }

        function hasMultipleEvents() {
            return new Set(getSelectedEventIds()).size > 1;
        }

        function setCurrentEvent() {
            const ids = getSelectedEventIds();
            const unique = [...new Set(ids)];
            currentEventId = unique.length === 1 ? unique[0] : null;
        }

        function updateAddButtonState() {
            const btn = document.getElementById('add-ticket-btn');
            const selectedIds = getSelectedTicketIds();
            const avail = allTickets.filter(t =>
                t.event_id == currentEventId &&
                !selectedIds.includes(String(t.id))
            );
            if (!currentEventId) {
                btn.disabled = true;
                btn.title = 'Select a ticket type first';
                return;
            }
            btn.disabled = avail.length === 0;
            btn.title = avail.length === 0
                ? 'No more ticket types for this event'
                : '';
        }

        function restrictSelectOptions() {
            const selects = document.querySelectorAll('.ticket-select');
            const selectedIds = getSelectedTicketIds();
            const single = selects.length === 1;

            selects.forEach(currentSelect => {
                const currentValue = currentSelect.value;

                Array.from(currentSelect.options).forEach(opt => {
                    const ticketId = opt.value;
                    const eventId = opt.dataset.eventId;

                    // Check if this ticket is selected in another dropdown
                    const isSelectedElsewhere = selectedIds.includes(ticketId) && ticketId !== currentValue;

                    // Check if this ticket is from a different event (only if we have multiple selects)
                    const isDifferentEvent = !single && currentEventId && eventId !== currentEventId;

                    // Disable and hide options that are selected elsewhere or from different events
                    opt.disabled = isSelectedElsewhere || isDifferentEvent;
                    opt.style.display = isSelectedElsewhere ? 'none' : '';
                });
            });
        }

        function updateIndices() {
            document.querySelectorAll('.ticket-item').forEach((div, i) => {
                div.querySelector('.ticket-select').name = `tickets[${i}][ticket_id]`;
                div.querySelector('.ticket-quantity').name = `tickets[${i}][quantity]`;
                div.querySelector('.ticket-price').name = `tickets[${i}][unit_price]`;
            });
        }

        function bindItem(div) {
            const sel = div.querySelector('.ticket-select');
            const qty = div.querySelector('.ticket-quantity');
            const price = div.querySelector('.ticket-price');
            const rem = div.querySelector('.remove-ticket');

            sel.addEventListener('change', e => {
                if (hasMultipleEvents()) {
                    alert('All tickets must be from the same event');
                    e.target.value = '';
                    return;
                }
                setCurrentEvent();
                restrictSelectOptions();
                const selectedOption = sel.selectedOptions[0];
                if (selectedOption) {
                    price.value = selectedOption.dataset.price;
                }
                updateAddButtonState();
                updateTotal();
            });

            qty.addEventListener('input', updateTotal);
            price.addEventListener('input', updateTotal);

            if (rem) rem.addEventListener('click', () => {
                div.remove();
                updateIndices();
                setCurrentEvent();
                restrictSelectOptions();
                updateAddButtonState();
                const items = document.querySelectorAll('.ticket-item');
                if (items.length === 1) {
                    const first = items[0].querySelector('.ticket-select');
                    first.innerHTML = allTickets.map(t => `<option value="${t.id}" data-price="${t.price}" data-event-id="${t.event_id}">${t.label}</option>`).join('');
                    first.dispatchEvent(new Event('change'));
                }
                updateTotal();
            });
        }

        document.querySelectorAll('.ticket-item').forEach(bindItem);

        document.getElementById('add-ticket-btn').addEventListener('click', () => {
            if (!currentEventId) return alert('Select a ticket type first');
            const selectedIds = getSelectedTicketIds();
            const avail = allTickets.filter(t => t.event_id == currentEventId && !selectedIds.includes(String(t.id)));
            if (avail.length === 0) return alert('No more ticket types for this event');

            const t = avail[0];
            const div = document.createElement('div');
            div.className = 'ticket-item relative bg-white p-4 rounded-xl shadow-sm border border-gray-200';
            div.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium mb-2">Ticket</label>
                        <select name="tickets[${ticketIndex}][ticket_id]" class="ticket-select w-full px-4 py-3 bg-white rounded-xl shadow-inner" required>
                            ${allTickets.filter(tu => tu.event_id == currentEventId && !selectedIds.includes(String(tu.id))).map(tu => `<option value="${tu.id}" data-price="${tu.price}" data-event-id="${tu.event_id}">${tu.label}</option>`).join('')}
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Quantity</label>
                        <input type="number" name="tickets[${ticketIndex}][quantity]" min="1" max="10" value="1" class="ticket-quantity w-full px-4 py-3 bg-white rounded-xl shadow-inner" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Unit Price</label>
                        <input type="number" name="tickets[${ticketIndex}][unit_price]" step="0.01" value="${t.price}" class="ticket-price w-full px-4 py-3 bg-white rounded-xl shadow-inner" required />
                    </div>
                </div>
                <button type="button" class="remove-ticket absolute top-2 right-2 text-red-500 hover:text-red-700">@svg('heroicon-o-x-mark','w-5 h-5')</button>
            `;
            document.getElementById('ticket-items').appendChild(div);
            bindItem(div);
            ticketIndex++;
            updateIndices();
            restrictSelectOptions();
            updateAddButtonState();
            updateTotal();
        });

        // Initialize on page load
        setTimeout(() => {
            setCurrentEvent();
            restrictSelectOptions();
            updateAddButtonState();
            updateTotal();
        }, 0);
    });
</script>
@endsection
