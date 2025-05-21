<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Database\Seeder;

class OrderItemsTableSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::all();
        $tickets = Ticket::all();

        foreach ($orders as $order) {
            $itemsCount = rand(1, 5);
            $usedTickets = [];

            for ($i = 0; $i < $itemsCount; $i++) {
                $ticket = $tickets->whereNotIn('id', $usedTickets)->random();
                $usedTickets[] = $ticket->id;

                $order->orderItems()->create([
                    'ticket_id' => $ticket->id,
                    'quantity' => rand(1, 3),
                    'unit_price' => $ticket->price,
                ]);
            }


            $order->updateTotalPrice();
        }
    }
}
