<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_template_id',
        'exam_id',
        'name',
        'description',
        'start_time',
        'end_time',
        'duration_minutes',
        'max_attempts',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function examTemplate(): BelongsTo
    {
        return $this->belongsTo(ExamTemplate::class);
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

