<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Controller;

class ViewComposerController extends Controller
{
    public function home()
    {
        return view('home', []);
    }

    public function getTasksByUser()
    {
        return view('tasks-by-user', []);
    }
}
