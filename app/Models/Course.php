<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;

class Course extends Model
{
    use HasFactory;

    public function tasks()
    {
        return $this->hasMany(Task::class, 'course_id');
    }
}
