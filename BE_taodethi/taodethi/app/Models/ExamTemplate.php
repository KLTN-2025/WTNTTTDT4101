<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'structure',
        'difficulty_distribution',
        'randomize_questions',
        'randomize_options',
        'unique_per_student',
        'total_questions',
        'total_points',
        'created_by',
    ];

    protected $casts = [
        'structure' => 'array',
        'difficulty_distribution' => 'array',
        'randomize_questions' => 'boolean',
        'randomize_options' => 'boolean',
        'unique_per_student' => 'boolean',
        'total_points' => 'decimal:2',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'exam_template_questions')
            ->withPivot('order', 'points')
            ->withTimestamps()
            ->orderBy('exam_template_questions.order');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(ExamSchedule::class);
    }
}

