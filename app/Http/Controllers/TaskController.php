<?php

namespace App\Http\Controllers;

use App\Services\CalculationService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Course;
use App\Models\TaskType;

class TaskController extends Controller
{
    protected $calculationService;

    public function __construct(CalculationService $calculationService)
    {
        $this->calculationService = $calculationService;
    }

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
        return redirect('/')->with('success', 'Task created and assigned to user successfully.');
    }

    public function getTasksByUser()
    {
        if (Auth::check()) {
            $user = Auth::user()->id;
            $userWithTasks = User::with(['tasks' => function ($query) {
                $query->with('course')->select('*'); // Include deadline and other necessary attributes
            }])->find($user);

            $tasks = $userWithTasks->tasks->map(function ($task) {
                $task->formatted_deadline = Carbon::parse($task->deadline)->format('d-m-Y');
                $task->uploadPath = $task->pivot->uploadPath;
                return $task;
            });
            return $tasks;
        }
    }


    public function confirmCompletion(Request $request)
    {
        $task = Task::find($request->task_id);
        $user = Auth::user();
        // Get the pivot record for the task and the authenticated user
        $pivotRecord = $task->users()->where('user_id', $user->id)->first();

        $requiredXp = $this->calculationService->calculateRequiredXp();
        $rewardMultiplier = $this->calculationService->calculateRewardMultiplier();


        if (!$task) {
            Log::error('Task not found for task_id: ' . $request->task_id);
            return redirect('/tasks-by-user')->with('error', 'Task not found.');
        }

        // dd($task, $user, $requiredXp, $rewardMultiplier, $pivotRecord);



        if (!$pivotRecord) {
            Log::error('Pivot record not found for user_id: ' . $user->id . ' and task_id: ' . $request->task_id);
            return redirect('/tasks-by-user')->with('error', 'Pivot record not found.');
        }

        // Update the pivot table to mark the task as completed
        $task->users()->updateExistingPivot($user->id, ['completed' => 1]);

        $submittedAt = Carbon::parse($pivotRecord->pivot->submitted_at);
        $deadline = Carbon::parse($task->deadline);
        $createdAt = Carbon::parse($task->created_at);


        if ($deadline >= $submittedAt) {
            $user->streakCount += 1;

            $timeDifference = $submittedAt->diffInSeconds($createdAt);
            $totalTime = $deadline->diffInSeconds($createdAt);

            if (($timeDifference / $totalTime) < 0.2) {
                $user->coins += 10 * $rewardMultiplier;
                $user->xp += 10 * $rewardMultiplier;
            } else {
                $user->coins += 5 * $rewardMultiplier;
                $user->xp += 5 * $rewardMultiplier;
            }
        } else {
            $user->streakCount = 0;
            $user->coins += 2;
            $user->xp += 2;
        }


        if ($user->xp >= $requiredXp) {
            $user->xp = $user->xp - $requiredXp;
            $user->coins += 100;
            $user->level += 1;
        }

        $user->save();

        return redirect('/tasks-by-user')->with('success', 'Task completed successfully.');
    }

    public function getAllTasksOfStudentsByTeacherId()
    {
        if (Auth::check()) {
            $teacherId = 9; // Replace with the actual teacher ID you want to test with
            $studentsByGroupByCourse = \App\Models\User::whereHas('courses', function ($courseQuery) use ($teacherId) {
                $courseQuery->where('user_id', $teacherId); // Adjust this column name as needed
            })->with([
                'courses' => function ($courseQuery) use ($teacherId) {
                    $courseQuery->where('user_id', $teacherId)->with([
                        'groups' => function ($groupQuery) {
                            $groupQuery->with([
                                'users' => function ($userQuery) {
                                    $userQuery->with([
                                        // Optionally, add additional conditions to tasks here
                                        // For example, you might filter tasks based on specific criteria
                                    ]);
                                }
                            ]);
                        }
                    ]);
                }
            ])->get();
            return view('teacher-group', compact('studentsByGroupByCourse'));
            // return $tasksByStudentByGroupByCourse;
            // dd($tasksByStudentByGroupByCourse);
        }
    }
}
