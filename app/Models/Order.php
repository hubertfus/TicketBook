<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_price',
        'status'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    /**
     * Relationship to the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship to order items (OrderItem)
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relationship to a refund (if any)
     */
    public function refund()
    {
        return $this->hasOne(Refund::class);
    }

    /**
     * Check if the order has a refund request
     */
    public function hasRefund(): bool
    {
        return $this->refund()->exists();
    }

    /**
     * Check if the refund has been approved
     */
    public function isRefundApproved(): bool
    {
        return $this->hasRefund() && $this->refund->isApproved();
    }

    /**
     * Scope for a specific status
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Update the total price of the order
     */
    public function updateTotalPrice(): void
    {
        $this->total_price = $this->orderItems->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });
        $this->save();
    }

    public function tickets()
    {
        return $this->belongsToMany(Ticket::class, 'order_items')
            ->withPivot('quantity', 'unit_price')
            ->withTimestamps();
    }
}
