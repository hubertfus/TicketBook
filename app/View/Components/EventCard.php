<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Event;

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
