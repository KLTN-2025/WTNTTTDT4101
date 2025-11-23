<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the exams assigned to the user.
     */
    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'user_exams')
            ->withPivot('status', 'submitted_at', 'duration_minutes', 'started_at', 'completed_at', 'score', 'max_score')
            ->withTimestamps();
    }

    /**
     * Get the scores for the user.
     */
    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    /**
     * Get the study progress for the user.
     */
    public function studyProgress()
    {
        return $this->hasMany(StudyProgress::class);
    }

    /**
     * Get the study suggestions for the user.
     */
    public function studySuggestions()
    {
        return $this->hasMany(StudySuggestion::class);
    }

    public function classes()
    {
        return $this->belongsToMany(Classroom::class, 'class_users', 'user_id', 'class_id')
            ->using(\App\Models\ClassUser::class)
            ->withPivot('role', 'student_code')
            ->withTimestamps();
    }

    public function taughtClasses()
    {
        return $this->hasMany(Classroom::class, 'teacher_id');
    }

    public function examAnswers()
    {
        return $this->hasMany(ExamAnswer::class);
    }

    public function examSessions()
    {
        return $this->hasMany(ExamSession::class);
    }

    public function questionFeedbacks()
    {
        return $this->hasMany(QuestionFeedback::class);
    }
}
