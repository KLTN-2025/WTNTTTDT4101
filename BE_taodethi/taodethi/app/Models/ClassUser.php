<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ClassUser extends Pivot
{
    protected $table = 'class_users';

    public $incrementing = true;

    protected $fillable = [
        'class_id',
        'user_id',
        'role',
        'student_code',
    ];

    public function getForeignKey()
    {
        return 'class_id';
    }

    public function getRelatedKey()
    {
        return 'user_id';
    }
}

