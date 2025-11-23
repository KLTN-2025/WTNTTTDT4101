<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'type',
        'difficulty_id',
        'options',
        'correct_answer',
        'explanation',
        'image_url',
        'audio_url',
        'video_url',
        'is_flagged',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'options' => 'array',
        'correct_answer' => 'array',
        'is_flagged' => 'boolean',
    ];

    public function difficulty(): BelongsTo
    {
        return $this->belongsTo(Difficulty::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'question_tags');
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'question_skills');
    }

    public function skillMatrix(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'skill_matrix')
            ->withPivot('weight', 'level', 'notes')
            ->withTimestamps();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(QuestionVersion::class);
    }

    public function examAnswers(): HasMany
    {
        return $this->hasMany(ExamAnswer::class);
    }
}

