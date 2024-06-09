<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Course;
use App\Models\TaskType;

class Task extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('uploadPath');
        
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function taskType()
    {
        return $this->belongsTo(TaskType::class, 'tasktype_id');
    }
}
