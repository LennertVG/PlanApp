<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\TaskType;
use App\Models\User;
use Carbon\Carbon;

class ViewComposerController extends Controller
{
    public function home()
    {
        return view('home', []);
    }

    public function getTasksByUser()
    {
        if (Auth::check()) {
            $user = Auth::user()->id;
            $userWithTasks = User::with('tasks.course', 'tasks.tasktype')->find($user);
            $tasks = $userWithTasks->tasks->map(function ($task) {
                $task->formatted_deadline = Carbon::parse($task->deadline)->format('d-m-Y');
                return $task;
            });

            $courses = Course::all();
            $taskTypes = TaskType::all();

            return view('your_view', compact('tasks', 'courses', 'taskTypes'));
        } else {
            return redirect()->route('login'); // Or return a specific view for non-authenticated users
        }
    }
}
