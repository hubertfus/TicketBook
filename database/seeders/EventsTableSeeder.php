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
                'date' => Carbon::now()->addDays(30)->toDateString(),
                'time' =>  Carbon::now()->addDays(30)->toTimeString(),
                'location' => 'National Stadium, Warsaw',
                'type' => 'festival',
                'last_minute' => false,
                'totalTickets' => rand(50, 200),
                'ticketSold' => rand(0, 50),
                'image' => 'https://loremflickr.com/cache/resized/defaultImage.small_320_240_nofilter.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organizer' => 'Sports Agency',
                'title' => 'International Football Match',
                'description' => 'Friendly match between national teams Poland vs Germany.',
                'date' => Carbon::now()->addDays(15)->toDateString(),
                'time' =>  Carbon::now()->addDays(15)->toTimeString(),
                'location' => 'PGE Narodowy, Warsaw',
                'type' => 'sport',
                'last_minute' => true,
                'totalTickets' => rand(50, 200),
                'ticketSold' => rand(0, 50),
                'image' => 'https://loremflickr.com/cache/resized/defaultImage.small_320_240_nofilter.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organizer' => 'Comedy Club',
                'title' => 'Stand-up Night',
                'description' => 'An evening full of laughter with top Polish comedians.',
                'date' => Carbon::now()->addDays(7)->toDateString(),
                'time' => Carbon::now()->addDays(7)->toTimeString(),
                'location' => 'Downtown Comedy Club, Krakow',
                'type' => 'standup',
                'last_minute' => false,
                'totalTickets' => rand(50, 200),
                'ticketSold' => rand(0, 50),
                'image' => 'https://loremflickr.com/cache/resized/defaultImage.small_320_240_nofilter.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organizer' => 'Music Promotions',
                'title' => 'Pop Concert',
                'description' => 'Popular artist performing his greatest hits live.',
                'date' => Carbon::now()->addDays(45)->toDateString(),
                'time' => Carbon::now()->addDays(45)->toTimeString(),
                'location' => 'Tauron Arena, Krakow',
                'type' => 'concert',
                'last_minute' => false,
                'totalTickets' => 123,
                'ticketSold' => 123,
                'image' => 'https://loremflickr.com/cache/resized/defaultImage.small_320_240_nofilter.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organizer' => 'Local Community',
                'title' => 'Charity Event',
                'description' => 'Annual charity event with various attractions.',
                'date' => Carbon::now()->addDays(60)->toDateString(),
                'time' =>  Carbon::now()->addDays(60)->toTimeString(),
                'location' => 'Main Square, Gdansk',
                'type' => 'other',
                'last_minute' => true,
                'totalTickets' => rand(50, 200),
                'ticketSold' => rand(0, 50),
                'image' => 'https://loremflickr.com/cache/resized/defaultImage.small_320_240_nofilter.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('events')->insert($events);
    }
}
