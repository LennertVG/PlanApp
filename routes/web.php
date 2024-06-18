<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Task;
use App\Models\User;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ViewComposerController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\RewardController;
use App\Http\Middleware\CheckForStudent;
use App\Http\Middleware\CheckForTeacher;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// LOGIN, REGISTRATION, ACCOUNT, BREEZE
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ADMIN VOYAGER FUNCTIONALITY
Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});


Route::middleware('auth:sanctum')->group(function () {

    // DASHBOARD VIEW
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    // APP FUNCTIONALITY
    // route for adding a task
    Route::get('/add-task', function () {
        return view('add-task');
    })->name('add-task');

    // retreiving tasks but returning in a different view
    Route::middleware('check.student')->get('/tasks-by-user', [ViewComposerController::class, 'getTasksByUsersForTasks'])->name('tasks-by-user');
    Route::get('/', [ViewComposerController::class, 'getTasksByUsersForHome'])->name('home');

    // retreiving tasks for teacher
    Route::middleware('check.teacher')->get('/teacher-tasks', [TaskController::class, 'getAllTasksOfStudentsByTeacherId'])->name('teacher-tasks');

    // route displaying your student's rewards
    Route::middleware('check.teacher')->get('/teacher-rewards', function () {
        return view('teacher-rewards');
    })->name('teacher-rewards');

    // route for marking a task as in progress
    Route::middleware('check.student')->post('/task/mark-in-progress/{task}', [TaskController::class, 'markTaskInProgress']);

    // route for storing a task
    Route::post('storeTask', [TaskController::class, 'store'])->name('task.store');

    // route for completing a task
    Route::middleware('check.teacher')->post('/complete-task', [TaskController::class, 'confirmCompletion'])->name('task.confirmCompletion');

    // route for uploading a file
    Route::post('/upload-task-file', [FileUploadController::class, 'uploadTaskFile']);

    // route for deleting a file
    Route::post('/delete-task-file', [FileUploadController::class, 'deleteTaskFile']);


    // route for using a reward
    Route::middleware('check.teacher')->post('/use-reward', [RewardController::class, 'useReward'])->name('task.useReward');

    Route::middleware('check.student')->get('/item-shop', function () {
        return view('item-shop');
    });
});



require __DIR__ . '/auth.php';
