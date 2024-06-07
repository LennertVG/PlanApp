<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function store(Request $request)
    {
        $task = new Task();

        $task->name = $request->name;
        $task->description = $request->description;
        $task->course_id = $request->course;
        $task->deadline = $request->deadline;
        $task->created_at = now();
        $task->updated_at = now();
        $task->createdBy = $request->createdBy;
        $task->tasktype_id = $request->taskType;

        $task->save();

        $task->users()->attach(Auth::id(), ['completed' => 0]);
        return redirect('/add-task')->with('success', 'Task created and assigned to user successfully.');
    }

    public function getTasksByUser()
    {
        if (Auth::check()) {
            $user = Auth::user()->id;
            $userWithTasks = User::with('tasks.course')->find($user);
            $tasks = $userWithTasks->tasks->map(function ($task) {
                $task->formatted_deadline = Carbon::parse($task->deadline)->format('d-m-Y');
                return $task;
            });
            return view('tasks_by_user', compact('tasks'));
        } else {
            return redirect('/'); // Redirect to home screen
        }
    }

    public function getUpcomingTasksByUser()
    {
        if (Auth::check()) {
            $user = Auth::user()->id;
            $userWithTasks = User::with('tasks.course')->find($user);
            $tasks = $userWithTasks->tasks->map(function ($task) {
                $task->formatted_deadline = Carbon::parse($task->deadline)->format('d-m-Y');
                return $task;
            });
            return view('home', ['tasks' => $tasks]); // Pass the tasks to the view
        } else {
            return redirect('/'); // Redirect to home screen
        }
    }
}
