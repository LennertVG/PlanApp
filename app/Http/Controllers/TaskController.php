<?php

namespace App\Http\Controllers;

use App\Services\CalculationService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use App\Models\Task;
use App\Models\User;
use App\Models\Course;
use App\Models\TaskType;

use App\Http\Controllers\FileUploadController;

use App\Mail\TaskSubmitted;

use Carbon\Carbon;

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
                $task->formatted_deadline = Carbon::parse($task->deadline)->format('d-m-Y | H:i');
                $task->uploadPath = $task->pivot->uploadPath;
                return $task;
            });
            return $tasks;
        }
    }

    public function markTaskInProgress(Request $request)
    {
        $task = Task::find($request->task_id);
        $user = Auth::user();

        if (!$task) {
            Log::error('Task not found for task_id: ' . $request->task_id);
            return redirect('/')->with('error', 'Task not found.');
        }

        // Update the completed status to 1 for in progress
        $task->users()->updateExistingPivot($user->id, ['completed' => 1, 'submitted_at' => now()]);

        // Retrieve the course associated with the task
        $course = $task->course;

        if (!$course) {
            Log::error('Course not found for task_id: ' . $request->task_id);
            return redirect('/')->with('error', 'Course not found.');
        }

        // Retrieve the teacher associated with the course
        $teacher = $course->users()->first();

        if (!$teacher) {
            Log::error('Teacher not found for course_id: ' . $course->id);
            return redirect('/')->with('error', 'Teacher not found.');
        }

        // Get the email of the teacher
        $teacherEmail = $teacher->email;

        // Retrieve the group associated with the task
        $group = $course->groups()->first();

        if (!$group) {
            Log::error('Group not found for course_id: ' . $course->id);
            return redirect('/')->with('error', 'Group not found.');
        }

        // Retrieve the grade and class from the groups table
        $grade = $group->grade;
        $class = $group->class;

        // Send email to the teacher
        Mail::to($teacherEmail)->send(new TaskSubmitted($task, $user, $grade, $class));
    }

    public function confirmCompletion(Request $request)
    {
        $task = Task::find($request->task_id);
        $user = User::find($request->student_id);
        $user_id = $request->student_id;
        // Get the pivot record for the task and the authenticated user
        $pivotRecord = $task->users()->where('user_id', $user->id)->first();

        $requiredXp = $this->calculationService->calculateRequiredXp($user_id);
        $rewardMultiplier = $this->calculationService->calculateRewardMultiplier($user_id);
        
        if (!$task) {
            Log::error('Task not found for task_id: ' . $request->task_id);
            return redirect('/tasks-by-user')->with('error', 'Task not found.');
        }

        if (!$pivotRecord) {
            Log::error('Pivot record not found for user_id: ' . $user->id . ' and task_id: ' . $request->task_id);
            return redirect('/tasks-by-user')->with('error', 'Pivot record not found.');
        }

        // Update the pivot table to mark the task as completed
        $task->users()->updateExistingPivot($user->id, ['completed' => 2]);

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

        session()->flash('success', 'Taak gemarkeerd als voltooid');

        return back();
    }

    public function getAllTasksOfStudentsByTeacherId()
    {
        if (Auth::check()) {

            $teacherId = Auth::user()->id;
            $studentsByGroupByCourse = \App\Models\User::whereHas('courses', function ($courseQuery) use ($teacherId) {
                $courseQuery->where('user_id', $teacherId); // Adjust this column name as needed
            })->with([
                'courses' => function ($courseQuery) use ($teacherId) {
                    $courseQuery->where('user_id', $teacherId)->with([
                        'groups' => function ($groupQuery) {
                            $groupQuery->with([
                                'users' => function ($userQuery) {
                                    $userQuery->with([]);
                                }
                            ]);
                        }
                    ]);
                }
            ])->get();
            return view('teacher-group', compact('studentsByGroupByCourse'));
        }
    }

    public function getTeacherEmailByTask($taskId)
    {
        if (Auth::check()) {
            // Retrieve the task
            $task = Task::find($taskId);

            if (!$task) {
                return response()->json(['error' => 'Task not found'], 404);
            }

            // Retrieve the course associated with the task
            $course = $task->course;

            if (!$course) {
                return response()->json(['error' => 'Course not found for the task'], 404);
            }

            // Retrieve the teacher associated with the course
            $teacher = $course->users()->first();

            if (!$teacher) {
                return response()->json(['error' => 'Teacher not found for the course'], 404);
            }

            // Return the teacher's email
            return response()->json(['teacher_email' => $teacher->email], 200);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
