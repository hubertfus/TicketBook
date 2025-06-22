<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Event;
use App\Models\Ticket;

class OrderItemsTableSeeder extends Seeder
{
    public function run(): void
    {
        // 0) Wyczyść wszystkie istniejące pozycje, żeby nie doklejać do starych
        DB::table('order_items')->truncate();

        $orders = Order::all();

        foreach ($orders as $order) {
            // 1) Wybierz losowo event, który ma bilety
            $event = Event::has('tickets')->inRandomOrder()->first();
            if (!$event) {
                continue;
            }

            // 2) Pobierz ID wszystkich biletów dla tego eventu
            $ticketIds = $event->tickets()->pluck('id')->toArray();

            // 3) Potasuj je
            shuffle($ticketIds);

            // 4) Wylosuj ile pozycji (1 do dostępnej liczby biletów)
            $max = count($ticketIds);
            $itemsCount = rand(1, $max);

            // 5) Weź pierwsze $itemsCount ID
            $selectedIds = array_slice($ticketIds, 0, $itemsCount);

            // 6) Dodaj je do order_items – każdy ticket_id tylko raz
            foreach ($selectedIds as $ticketId) {
                $ticket = Ticket::findOrFail($ticketId);
                $order->orderItems()->create([
                    'ticket_id'  => $ticket->id,
                    'quantity'   => rand(1, 3),
                    'unit_price' => $ticket->price,
                ]);
            }

            // 7) Przelicz cenę zamówienia
            $order->updateTotalPrice();
        }
    }
}
