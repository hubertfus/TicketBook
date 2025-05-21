<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Refund;
use Illuminate\Database\Seeder;

class RefundsTableSeeder extends Seeder
{
    public function run(): void
    {
        $orders = Order::where('status', 'paid')->get();
        $reasons = [
            'Change of plans',
            'Too expensive',
            'Found a better offer',
            'Availability issues',
            'Other reason'
        ];

        $refunds = [];

        foreach ($orders as $order) {
            if (rand(1, 100) <= 30) {
                $refunds[] = [
                    'order_id' => $order->id,
                    'reason' => $reasons[array_rand($reasons)],
                    'status' => $this->getRandomStatus(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Refund::insert($refunds);
    }

    private function getRandomStatus(): string
    {
        $statuses = ['requested', 'approved', 'rejected', 'processed'];
        $weights = [30, 40, 20, 10];

        $rand = rand(1, array_sum($weights));
        $current = 0;

        foreach ($weights as $key => $weight) {
            $current += $weight;
            if ($rand <= $current) {
                return $statuses[$key];
            }
        }

        return 'requested';
    }
}
