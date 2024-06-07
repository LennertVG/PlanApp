<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Controller;

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
