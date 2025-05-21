<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'rating',
        'comment'
    ];

    /**
     * Relationship to the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship to the event
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Relationship to the event
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Calculates the average rating of related reviews
     */
    public function getAverageRatingAttribute(): float
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Gets the number of related reviews
     */
    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->count();
    }

    /**
     * Scope for a specific rating
     */
    public function scopeWithRating($query, int $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Scope for reviews that include comments
     */
    public function scopeWithComments($query)
    {
        return $query->whereNotNull('comment');
    }

    /**
     * Check if the review has a comment
     */
    public function hasComment(): bool
    {
        return !empty($this->comment);
    }
}
