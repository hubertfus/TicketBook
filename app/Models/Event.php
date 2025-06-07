<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer',
        'title',
        'description',
        'date',
        'time',
        'location',
        'type',
        'last_minute',
        'totalTickets',
        'ticketSold',
        'image'
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i',
        'last_minute' => 'boolean',
        'totalTickets' => 'integer',
        'ticketSold' => 'integer',
    ];

    /**
     * One-to-many relationship with tickets
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'event_id', 'id');
    }


    /**
     * Scope for last-minute events
     */
    public function scopeLastMinute($query)
    {
        return $query->where('last_minute', true);
    }

    /**
     * Scope for a specific event type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}
