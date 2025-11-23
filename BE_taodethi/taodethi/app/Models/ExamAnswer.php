<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ExamAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'exam_id',
        'question_id',
        'answer',
        'selected_options',
        'points_earned',
        'is_correct',
        'teacher_feedback',
    ];

    protected $casts = [
        'selected_options' => 'array',
        'points_earned' => 'decimal:2',
        'is_correct' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function essayGrade(): HasOne
    {
        return $this->hasOne(ExamEssayGrade::class);
    }
}
