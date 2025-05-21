<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class FavoritesTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::limit(10)->get();
        $events = Event::all();

        foreach ($users as $user) {
            $favoriteEvents = $events->random(rand(3, 5));

            foreach ($favoriteEvents as $event) {
                $user->favorites()->firstOrCreate([
                    'event_id' => $event->id
                ]);
            }
        }
    }
}
