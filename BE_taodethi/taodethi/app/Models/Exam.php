<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subject',
        'description',
        'start_date',
        'due_date',
        'status',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'due_date' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_exams')
            ->withPivot('status', 'submitted_at', 'duration_minutes', 'started_at', 'completed_at', 'score', 'max_score')
            ->withTimestamps();
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(ExamSession::class);
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(QuestionFeedback::class);
    }

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'exam_questions')
            ->withPivot('order', 'points')
            ->withTimestamps()
            ->orderBy('exam_questions.order');
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(ExamSchedule::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(ExamAnswer::class);
    }
}

