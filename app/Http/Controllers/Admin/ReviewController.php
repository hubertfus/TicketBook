<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'event'])
            ->latest();

        if ($request->filled('search')) {
            $search = strtolower($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(comment) LIKE ?', ["%{$search}%"])
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"]);
                    })
                    ->orWhereHas('event', function ($q) use ($search) {
                        $q->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"]);
                    });
            });
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->input('rating'));
        }

        if ($request->filled('event')) {
            $query->where('event_id', $request->input('event'));
        }

        $reviews = $query->paginate(10);
        $ratings = range(1, 5);
        $events = Event::orderBy('title')->get();

        return view('pages.admin.reviews.index', [
            'reviews' => $query->paginate(10),
            'ratings' => range(1, 5),
            'events' => Event::orderBy('title')->get(),
            'totalReviews' => Review::count(),
            'averageRating' => Review::avg('rating') ?? 0,
            'lastReviewDate' => Review::latest()->first()?->created_at
        ]);
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        $events = Event::orderBy('title')->get();
        return view('pages.admin.reviews.create', compact('users', 'events'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'max:1000',
        ]);

        Review::create($validated);

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review added successfully!');
    }

    public function edit(Review $review)
    {
        return view('pages.admin.reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'max:1000',
        ]);

        $review->update($validated);

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review updated successfully!');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review deleted successfully!');
    }
}
