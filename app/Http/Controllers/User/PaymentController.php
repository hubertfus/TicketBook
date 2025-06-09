<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function show(Event $event)
    {
        $purchaseData = session('purchase_data');

        if (!$purchaseData || $purchaseData['event_id'] != $event->id) {
            return redirect()->route('events.show', $event)->with('error', 'No ticket selection found.');
        }

        return view('pages.user.events.payment', [
            'event' => $event,
            'tickets' => $purchaseData['tickets'],
            'total' => $purchaseData['total'],
        ]);
    }

    public function pay(Request $request, Event $event)
    {
        $purchaseData = session('purchase_data');

        if (!$purchaseData || $purchaseData['event_id'] != $event->id) {
            return redirect()->route('events.show', $event)->with('error', 'No ticket selection found.');
        }

        $user = auth()->user();

        if ($user->balance < $purchaseData['total']) {
            return back()->withErrors(['balance' => 'Insufficient balance to complete the purchase.']);
        }

        DB::transaction(function () use ($user, $event, $purchaseData) {
            $user->balance -= $purchaseData['total'];
            $user->save();

            $order = $user->orders()->create([
                'event_id' => $event->id,
                'total_price' => $purchaseData['total'],
                'status' => 'paid',
            ]);

            foreach ($purchaseData['tickets'] as $item) {
                $order->tickets()->attach($item['ticket_id'], [
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                ]);

                $ticket = Ticket::find($item['ticket_id']);
                $ticket->quantity -= $item['quantity'];
                $ticket->save();
            }

            $totalTicketsBought = collect($purchaseData['tickets'])->sum('quantity');

            $event->ticketSold += $totalTicketsBought;
            $event->save();
        });

        session()->forget('purchase_data');

        return redirect()->route('user.orders.index', $event)->with('success', 'Payment successful. Tickets purchased!');
    }
}
