<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudyProgress extends Model
{
    use HasFactory;

    protected $table = 'study_progress';

    protected $fillable = [
        'user_id',
        'subject',
        'progress_percentage',
        'total_lessons',
        'completed_lessons',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

