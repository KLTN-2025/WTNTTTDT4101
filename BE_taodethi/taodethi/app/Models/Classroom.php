<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Classroom extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'code',
        'description',
        'subject',
        'academic_year',
        'teacher_id',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_users', 'class_id', 'user_id')
            ->using(ClassUser::class)
            ->withPivot('role', 'student_code')
            ->where('class_users.role', 'student')
            ->withTimestamps();
    }

    public function assistantTeachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_users', 'class_id', 'user_id')
            ->using(ClassUser::class)
            ->withPivot('role', 'student_code')
            ->where('class_users.role', 'assistant_teacher')
            ->withTimestamps();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_users', 'class_id', 'user_id')
            ->using(ClassUser::class)
            ->withPivot('role', 'student_code')
            ->withTimestamps();
    }
}
