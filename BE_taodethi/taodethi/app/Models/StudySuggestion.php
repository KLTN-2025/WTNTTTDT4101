<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudySuggestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'subject',
        'type',
        'priority',
        'is_completed',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

