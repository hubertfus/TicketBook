<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('orders')->truncate();

        $users = User::limit(5)->get();
        $statuses = ['pending', 'paid', 'cancelled', 'refunded'];

        $orders = [];

        foreach ($users as $user) {
            $orders[] = [
                'user_id' => $user->id,
                'total_price' => 0,
                'status' => $statuses[array_rand($statuses)],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }


        $createdOrders = [];
        foreach ($orders as $order) {
            $createdOrders[] = Order::create($order);
        }

        foreach ($createdOrders as $order) {
            $itemsCount = rand(1, 5);

            for ($i = 0; $i < $itemsCount; $i++) {
                $order->orderItems()->create([
                    'ticket_id' => rand(1, 15),
                    'quantity' => rand(1, 3),
                    'unit_price' => rand(50, 200),
                ]);
            }

            // Aktualizacja łącznej ceny
            $order->updateTotalPrice();
        }
    }
}
