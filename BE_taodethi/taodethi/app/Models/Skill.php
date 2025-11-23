<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
    ];

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'question_skills');
    }

    public function skillMatrix(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'skill_matrix')
            ->withPivot('weight', 'level', 'notes')
            ->withTimestamps();
    }
}

