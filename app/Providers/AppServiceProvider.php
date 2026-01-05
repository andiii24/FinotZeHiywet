<?php

namespace App\Providers;

use App\Models\PlanningTask;
use App\Observers\PlanningTaskObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Register model observers
        PlanningTask::observe(PlanningTaskObserver::class);
    }
}
