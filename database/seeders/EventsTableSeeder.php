<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'organizer' => 'Live Nation',
                'title' => 'Rock Festival 2023',
                'description' => 'Annual rock festival featuring top international and local bands.',
                'start_time' => Carbon::now()->addDays(30),
                'location' => 'National Stadium, Warsaw',
                'type' => 'festival',
                'last_minute' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organizer' => 'Sports Agency',
                'title' => 'International Football Match',
                'description' => 'Friendly match between national teams Poland vs Germany.',
                'start_time' => Carbon::now()->addDays(15),
                'location' => 'PGE Narodowy, Warsaw',
                'type' => 'sport',
                'last_minute' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organizer' => 'Comedy Club',
                'title' => 'Stand-up Night',
                'description' => 'An evening full of laughter with top Polish comedians.',
                'start_time' => Carbon::now()->addDays(7),
                'location' => 'Downtown Comedy Club, Krakow',
                'type' => 'standup',
                'last_minute' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organizer' => 'Music Promotions',
                'title' => 'Pop Concert',
                'description' => 'Popular artist performing his greatest hits live.',
                'start_time' => Carbon::now()->addDays(45),
                'location' => 'Tauron Arena, Krakow',
                'type' => 'concert',
                'last_minute' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organizer' => 'Local Community',
                'title' => 'Charity Event',
                'description' => 'Annual charity event with various attractions.',
                'start_time' => Carbon::now()->addDays(60),
                'location' => 'Main Square, Gdansk',
                'type' => 'other',
                'last_minute' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('events')->insert($events);
    }
}
