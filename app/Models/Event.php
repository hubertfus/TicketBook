<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public $timestamps = true;


    protected $fillable = [
        'title',
        'date',
        'time',
        'type',
        'description',
        'image',
        'totalTickets',
        'ticketSold',
        'status',
        'venue',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i',
        'totalTickets' => 'integer',
        'ticketSold' => 'integer',
    ];
}
