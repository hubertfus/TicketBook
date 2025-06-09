<?php


namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query();

        if ($request->filled('date')) {
            $query->whereDate('date', $request->input('date'));
        }

        if ($request->filled('location')) {
            $location = strtolower($request->input('location'));
            $query->whereRaw('LOWER(location) LIKE ?', ["%{$location}%"]);
        }

        if ($request->filled('title')) {
            $title = strtolower($request->input('title'));
            $query->whereRaw('LOWER(title) LIKE ?', ["%{$title}%"]);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        $events = $query->latest()->paginate(10);
        $types = Event::select('type')->distinct()->pluck('type');

        $suggestedEvents = collect();
        $commonType = null;

        $isAdmin = Auth::check() && Auth::user()->role === 'admin';

        if (Auth::check()) {
            $user = Auth::user();
            $purchasedEventIds = collect();

            if ($user) {
                $purchasedEventIds = $user->orders()
                    ->with('orderItems.ticket.event')
                    ->get()
                    ->flatMap(function ($order) {
                        return $order->orderItems->map(function ($item) {
                            return $item->ticket->event_id;
                        });
                    })
                    ->unique()
                    ->values();

                // Najczęściej kupowany typ wydarzenia
                $commonType = Event::whereIn('id', $purchasedEventIds)
                    ->groupBy('type')
                    ->selectRaw('type, count(*) as total')
                    ->orderByDesc('total')
                    ->pluck('type')
                    ->first();

                // Sugestie wydarzeń
                if ($commonType) {
                    $suggestedEvents = Event::where('type', $commonType)
                        ->whereNotIn('id', $purchasedEventIds)
                        ->latest('date')
                        ->limit(3)
                        ->get();
                }
            }
        }

        return view('pages.user.events.index', compact('events', 'types', 'suggestedEvents', 'commonType', 'isAdmin'));
    }

    public function show(Event $event)
    {
        return view('pages.user.events.show', compact('event'));
    }
}
