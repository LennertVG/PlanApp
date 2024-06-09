<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\TaskType;
use App\Models\User;
use Carbon\Carbon;

class ViewComposerController extends Controller
{
    public function getTasksByUsersForHome()
    {
        return view('home', []);
    }

    public function getTasksByUsersForTasks()
    {
        return view('tasks-by-user', []);
    }
}
