<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'exam_id',
        'question_order',
        'answers',
        'remaining_seconds',
        'camera_enabled',
        'camera_locked',
        'started_at',
        'paused_at',
        'resumed_at',
    ];

    protected $casts = [
        'question_order' => 'array',
        'answers' => 'array',
        'camera_enabled' => 'boolean',
        'camera_locked' => 'boolean',
        'started_at' => 'datetime',
        'paused_at' => 'datetime',
        'resumed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }
}

