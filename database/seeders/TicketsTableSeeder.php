<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Event;

class TicketsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tickets')->truncate();

        $events = Event::all();

        $tickets = [];

        foreach ($events as $event) {
            $tickets[] = [
                'event_id' => $event->id,
                'category' => 'standard',
                'price' => rand(50, 150),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $tickets[] = [
                'event_id' => $event->id,
                'category' => 'vip',
                'price' => rand(200, 500),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $tickets[] = [
                'event_id' => $event->id,
                'category' => 'student',
                'price' => rand(30, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('tickets')->insert($tickets);
    }
}
