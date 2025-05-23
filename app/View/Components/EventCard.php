<?php

namespace App\View\Components;

use App\Models\Event;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EventCard extends Component
{
    public Event $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function render(): View|Closure|string
    {
        return view('components.event-card');
    }
}
