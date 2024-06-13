<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use TCG\Voyager\Models\User as VoyagerUser;

class User extends VoyagerUser
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'firstname',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function tasks()
    {
        return $this->belongsToMany(Task::class)->withPivot('uploadPath', 'completed');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }
    public function rewards()
    {
        return $this->belongstoMany(Reward::class)->withPivot('amount');
    }
}
