<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
public function store(Request $request, Event $event)
{
    $user = auth()->user();

    if (!$user || !$user->hasAttendedEvent($event->id)) {
        return redirect()->back()->with('error', 'You must attend the event to leave a review.');
    }

    // Jeśli nie wybrano oceny – ręczny redirect z komunikatem
    if (!$request->filled('rating')) {
        return redirect()->back()
                         ->withInput()
                         ->with('error', 'Please select a rating before submitting your review.');
    }

    // Sprawdzenie, czy opinia już istnieje
    $existingReview = Review::where('user_id', $user->id)
                            ->where('event_id', $event->id)
                            ->first();

    if ($existingReview) {
        return redirect()->back()->with('error', 'You have already submitted a review for this event.');
    }

    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:1000',
    ]);

    Review::create([
        'user_id' => $user->id,
        'event_id' => $event->id,
        'rating' => $request->input('rating'),
        'comment' => $request->input('comment'),
    ]);

    return redirect()->back()->with('success', 'Thank you for your review!');
}


}
