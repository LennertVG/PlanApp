<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\App;

class TasksByUserComposer
{

    protected $tasks = [];

    public function __construct()
    {
        //
    }

    // this method binds data to the view
    public function getTasksByUser()
    {
        // Instantiate TaskController and call the method to get tasks
        $taskController = App::make(TaskController::class);
        $this->tasks = $taskController->getTasksByUser();
    }

    public function compose(View $view)
    {
        $this->getTasksByUser();
        $view->with('tasks', $this->tasks);
    }
}
