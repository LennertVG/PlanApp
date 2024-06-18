<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
use App\Models\Group;
use App\Models\User;

class Course extends Model
{
    
    use HasFactory;

    public function tasks()
    {
        return $this->hasMany(Task::class, 'course_id');
    }

    public function groups()
    {
        return $this->belongstoMany(Group::class);
    }

    public function users()
    {
        return $this->belongstoMany(User::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'course_user', 'course_id', 'user_id')
                    ->wherePivot('role_id', 3);
    }
}
