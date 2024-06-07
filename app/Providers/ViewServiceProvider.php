<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\TaskController;
use App\Http\ViewComposers\TasksByUserComposer;
use Illuminate\Support\Facades\Auth;

class ViewServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        // this * will retrieve data for all the views
        View::composer('*', TasksByUserComposer::class);
    }
}
