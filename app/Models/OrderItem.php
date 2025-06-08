<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'ticket_id',
        'quantity',
        'unit_price'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
    ];

    /**
     * Relationship to the order
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relationship to the ticket
     */
    public function tickets()
    {
        return $this->belongsToMany(Ticket::class, 'order_items')
            ->withPivot('quantity', 'unit_price')
            ->withTimestamps();
    }

    /**
     * Calculates the total price for the order item
     */
    public function getTotalPriceAttribute(): float
    {
        return $this->quantity * $this->unit_price;
    }

    /**
     * Scope for a specific ticket type
     */
    public function scopeTicketType($query, string $type)
    {
        return $query->whereHas('ticket', function ($q) use ($type) {
            $q->where('type', $type);
        });
    }
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
