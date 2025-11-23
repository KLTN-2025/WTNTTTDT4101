<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamEssayGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_answer_id',
        'graded_by',
        'score',
        'max_score',
        'rubric_scores',
        'feedback',
        'ai_suggestion',
        'status',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'max_score' => 'decimal:2',
        'rubric_scores' => 'array',
    ];

    public function examAnswer(): BelongsTo
    {
        return $this->belongsTo(ExamAnswer::class);
    }

    public function grader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }
}
