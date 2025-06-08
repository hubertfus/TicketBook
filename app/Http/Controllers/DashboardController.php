<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\OrderItem;
use App\Models\Order;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $events = Event::with(['tickets.orderItems' => function($query) {
                $query->whereHas('order', function($q) {
                    $q->where('status', 'paid');
                });
            }])
            ->get()
            ->map(function($event) {
                $event->total_sold = $event->tickets->flatMap->orderItems->sum('quantity');
                $event->total_revenue = $event->tickets->flatMap->orderItems->sum(function($item) {
                    return $item->quantity * $item->unit_price;
                });
                return $event;
            })
            ->sortByDesc('total_sold')
            ->take(5);

        $eventLabels = $events->pluck('title');
        $eventData = $events->pluck('total_sold');

        $monthlyItems = OrderItem::with('order')
            ->whereHas('order', function($query) {
                $query->where('status', 'paid');
            })
            ->get()
            ->groupBy(function($item) {
                return $item->created_at->format('Y-m');
            })
            ->map(function($group, $key) {
                return [
                    'year' => $group->first()->created_at->year,
                    'month' => $group->first()->created_at->month,
                    'total_tickets' => $group->sum('quantity'),
                    'total_revenue' => $group->sum(function($item) {
                        return $item->quantity * $item->unit_price;
                    })
                ];
            })
            ->sortBy(['year', 'month'])
            ->values();

        $orderStatuses = Order::select('status')
            ->selectRaw('count(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        $statusLabels = ['paid', 'pending', 'refunded'];
        $statusData = [
            $orderStatuses->get('paid', 0),
            $orderStatuses->get('pending', 0),
            $orderStatuses->get('refunded', 0)
        ];

        $monthlyLabels = $monthlyItems->map(function ($item) {
            return Carbon::create()
                ->year($item['year'])
                ->month($item['month'])
                ->format('F Y');
        });

        $monthlyTicketsData = $monthlyItems->pluck('total_tickets');
        $monthlyRevenueData = $monthlyItems->pluck('total_revenue');

        return view('pages.admin.dashboard', [
            'eventLabels' => $eventLabels,
            'eventData' => $eventData,
            'monthlyLabels' => $monthlyLabels,
            'monthlyTicketsData' => $monthlyTicketsData,
            'monthlyRevenueData' => $monthlyRevenueData,
            'statusLabels' => $statusLabels,
            'statusData' => $statusData,
        ]);
    }
}
