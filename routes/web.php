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
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



// APP FUNCTIONALITY
Route::get('/add-task', function () {
    return view('add-task');
})->name('add-task');

Route::get('/tasks-by-user', [ViewComposerController::class, 'getTasksByUsersForTasks'])->name('tasks-by-user');
Route::get('/', [ViewComposerController::class, 'getTasksByUsersForHome'])->name('home');

Route::post('storeTask', [TaskController::class, 'store'])->name('task.store');

// route for completing a task
Route::post('/complete-task', [TaskController::class, 'confirmCompletion'])->name('task.confirmCompletion');

Route::post('/upload-task-file', [FileUploadController::class, 'uploadTaskFile']);

// ADMIN VOYAGER FUNCTIONALITY
Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});


require __DIR__ . '/auth.php';
