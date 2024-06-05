<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\User;

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
}
