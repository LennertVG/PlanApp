<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CalculationService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CalculationService::class, function ($app) {
            return new CalculationService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
