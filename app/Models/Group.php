<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\User;

class Group extends Model
{
    use HasFactory;

    public function courses()
    {
        return $this->belongstoMany(Course::class);
    }

    public function users()
    {
        return $this->belongstoMany(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
