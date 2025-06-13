<?php

namespace App\Http\Controllers\User;


use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Event;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'event'])
            ->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', "%$search%")
                    ->orWhereHas('event', function ($q) use ($search) {
                        $q->where('title', 'like', "%$search%");
                    })
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%");
                    });
            });
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->input('rating'));
        }

        if ($request->filled('event')) {
            $query->where('event_id', $request->input('event'));
        }

        return view('pages.admin.reviews.index', [
            'reviews' => $query->paginate(10),
            'ratings' => range(1, 5),
            'events' => Event::orderBy('title')->get(),
            'totalReviews' => Review::count(),
            'averageRating' => Review::avg('rating') ?? 0,
            'lastReviewDate' => Review::latest()->first()?->created_at
        ]);


    }

    public function destroy(Review $review)
    {
        $review->delete();

        if (auth()->id() === $review->user_id) {
            $review->delete();
            return back()->with('success', 'Review has been deleted successfully!');
        }
        return back()->with('success', 'You do not have permission to delete this review.');
    }


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
