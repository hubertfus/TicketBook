<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopUpCode extends Model
{
    protected $fillable = [
        'code',
        'value',
        'is_used',
        'used_by',
        'used_at',
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'used_at' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'used_by');
    }
}
