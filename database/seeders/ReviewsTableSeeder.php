<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewsTableSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $events = Event::all();

        $reviews = [];

        foreach ($events as $event) {
            $reviewers = $users->random(rand(2, 5));

            foreach ($reviewers as $user) {
                $reviews[] = [
                    'user_id' => $user->id,
                    'event_id' => $event->id,
                    'rating' => rand(3, 5),
                    'comment' => rand(0, 1) ? $this->generateComment() : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Review::insert($reviews);
    }

    private function generateComment(): string
    {
        $comments = [
            'Great event, highly recommend!',
            'Very well organized.',
            'Interesting program, worth attending.',
            'I will be there next time too!',
            'A bit long queues, but worth it.',
            'Awesome atmosphere!',
            'Had a good time.',
            'Could be better, but no complaints.',
            'Worth the price.',
            'Recommended to everyone!'
        ];

        return $comments[array_rand($comments)];
    }
}
