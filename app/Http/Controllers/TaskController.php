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
    public function confirmCompletion(Request $request)
    {
        $task = Task::find($request->task_id);
        
        if (!$task) {
            Log::error('Task not found for task_id: ' . $request->task_id);
            return redirect('/tasks-by-user')->with('error', 'Task not found.');
        }
    
        $user = Auth::user();
    
        // Get the pivot record for the task and the authenticated user
        $pivotRecord = $task->users()->where('user_id', $user->id)->first();
    
        if (!$pivotRecord) {
            Log::error('Pivot record not found for user_id: ' . $user->id . ' and task_id: ' . $request->task_id);
            return redirect('/tasks-by-user')->with('error', 'Pivot record not found.');
        }
    
        // Update the pivot table to mark the task as completed
        $task->users()->updateExistingPivot($user->id, ['completed' => 1]);
    
        $submittedAt = Carbon::parse($pivotRecord->pivot->submitted_at);
        $deadline = Carbon::parse($task->deadline);
        $createdAt = Carbon::parse($task->created_at);
        // dd($submittedAt, $deadline, $createdAt);
    
        if ($submittedAt < $deadline) {
            $user->streakCount += 1;

            $timeDifference = $submittedAt->diffInSeconds($createdAt);
            $totalTime = $deadline->diffInSeconds($createdAt);
            // dd($timeDifference, $totalTime);
    
            if (($timeDifference / $totalTime) < 0.2) {
                $user->coins += 10;
                $user->xp += 10;
            } else {
                $user->coins += 5;
                $user->xp += 5;
            }
        } else {
            $user->streakCount = 0;
            $user->coins += 2;
            $user->xp += 2;
        }
    
        if ($user->xp >= 100) {
            $user->xp = $user->xp - 100;
            $user->coins += 100;
            $user->level += 1;
        }
    
        $user->save();
    
        return redirect('/tasks-by-user')->with('success', 'Task completed successfully.');
    }
}