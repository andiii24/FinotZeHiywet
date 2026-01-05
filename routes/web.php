<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GroupCatController;
use App\Http\Controllers\MonthlyPaymentController;
use App\Http\Controllers\SocialContributionCategoryController;
use App\Http\Controllers\SocialContributionController;
use App\Http\Controllers\SocialContributorsController;
use App\Http\Controllers\JobCategoryController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\EventsCategoryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\PlanningController;
use App\Http\Controllers\PlanningTaskController;
use App\Http\Controllers\PlanningBudgetController;
use App\Http\Controllers\PlanningReminderController;
use App\Http\Controllers\PlanningTeamController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\GroupCatController as AdminGroupCatController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\MonthlyPaymentSettingController;
use App\Http\Controllers\Admin\SystemSettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Group_cats routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::resource('group_cats', GroupCatController::class);

    // Monthly payments routes
    Route::resource('monthly-payments', MonthlyPaymentController::class);
    Route::post('monthly-payments/{id}/status', [MonthlyPaymentController::class, 'updateStatus'])->name('monthly-payments.status');
    Route::get('monthly-payments-backlog', [MonthlyPaymentController::class, 'backlog'])->name('monthly-payments.backlog');

    // Admin routes for user backlog
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('admin/user-backlog/{userId}', [MonthlyPaymentController::class, 'getUserBacklog'])->name('admin.user-backlog');
    });

    // Social contribution routes
    // Categories are already guarded by controller middleware, but keep routes here
    Route::resource('social-contribution-categories', SocialContributionCategoryController::class);
    // Social contributions: list and show open to all auth; create/update/delete guarded by controller middleware
    Route::resource('social-contributions', SocialContributionController::class)->only(['index','show','create','store','edit','update','destroy']);
    Route::resource('social-contributors', SocialContributorsController::class);

    // Special route to add a user as a contributor to a social contribution
    Route::post('social-contributions/{contribution}/contribute', [SocialContributorsController::class, 'contribute'])
        ->name('social-contributions.contribute');

    // Job routes
    Route::resource('job-categories', JobCategoryController::class);
    Route::resource('jobs', JobController::class);

    // Event routes
    Route::resource('event-categories', EventsCategoryController::class);
    Route::resource('events', EventController::class);

    // Task routes
    Route::resource('tasks', TaskController::class);

    // Planning routes
    Route::resource('plannings', PlanningController::class);
    Route::get('plannings/{planning}/gantt', [PlanningController::class, 'gantt'])->name('plannings.gantt');
    Route::get('plannings/{planning}/calendar', [PlanningController::class, 'calendar'])->name('plannings.calendar');
    Route::get('planning-dashboard', [PlanningController::class, 'dashboard'])->name('plannings.dashboard');

    // Planning delete routes
    Route::get('plannings-trash', [PlanningController::class, 'trashed'])->name('plannings.trashed');
    Route::post('plannings/{planning}/restore', [PlanningController::class, 'restore'])->name('plannings.restore');
    Route::delete('plannings/{planning}/force-delete', [PlanningController::class, 'forceDelete'])->name('plannings.force-delete');

    // Planning Tasks routes
    Route::get('plannings/{planning}/tasks', [PlanningTaskController::class, 'index'])->name('plannings.tasks.index');
    Route::post('plannings/{planning}/tasks', [PlanningTaskController::class, 'store'])->name('plannings.tasks.store');
    Route::put('plannings/{planning}/tasks/{planningTask}', [PlanningTaskController::class, 'update'])->name('plannings.tasks.update');
    Route::delete('plannings/{planning}/tasks/{planningTask}', [PlanningTaskController::class, 'destroy'])->name('plannings.tasks.destroy');

    // Planning Budget routes
    Route::get('plannings/{planning}/budgets', [PlanningBudgetController::class, 'index'])->name('plannings.budgets.index');
    Route::post('plannings/{planning}/budgets', [PlanningBudgetController::class, 'store'])->name('plannings.budgets.store');
    Route::put('plannings/{planning}/budgets/{planningBudget}', [PlanningBudgetController::class, 'update'])->name('plannings.budgets.update');
    Route::delete('plannings/{planning}/budgets/{planningBudget}', [PlanningBudgetController::class, 'destroy'])->name('plannings.budgets.destroy');

    // Planning Reminders routes
    Route::get('plannings/{planning}/reminders', [PlanningReminderController::class, 'index'])->name('plannings.reminders.index');
    Route::post('plannings/{planning}/reminders', [PlanningReminderController::class, 'store'])->name('plannings.reminders.store');
    Route::put('plannings/{planning}/reminders/{planningReminder}', [PlanningReminderController::class, 'update'])->name('plannings.reminders.update');
    Route::delete('plannings/{planning}/reminders/{planningReminder}', [PlanningReminderController::class, 'destroy'])->name('plannings.reminders.destroy');
    Route::post('plannings/{planning}/reminders/{planningReminder}/mark-sent', [PlanningReminderController::class, 'markSent'])->name('plannings.reminders.mark-sent');

    // Planning Team routes
    Route::get('plannings/{planning}/team', [PlanningTeamController::class, 'index'])->name('plannings.team.index');
    Route::post('plannings/{planning}/team', [PlanningTeamController::class, 'store'])->name('plannings.team.store');
    Route::put('plannings/{planning}/team/{user}', [PlanningTeamController::class, 'update'])->name('plannings.team.update');
    Route::delete('plannings/{planning}/team/{user}', [PlanningTeamController::class, 'destroy'])->name('plannings.team.destroy');

});


// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin group_cats management
    Route::resource('group_cats', AdminGroupCatController::class);

    // Admin users management
    Route::resource('users', AdminUserController::class);

    // Monthly payment settings management
    Route::get('monthly-payment-settings', [MonthlyPaymentSettingController::class, 'index'])->name('monthly-payment-settings.index');
    Route::get('monthly-payment-settings/edit', [MonthlyPaymentSettingController::class, 'edit'])->name('monthly-payment-settings.edit');
    Route::put('monthly-payment-settings', [MonthlyPaymentSettingController::class, 'update'])->name('monthly-payment-settings.update');

    // System settings
    Route::get('system-settings', [SystemSettingsController::class, 'index'])->name('system-settings.index');
    Route::put('system-settings', [SystemSettingsController::class, 'update'])->name('system-settings.update');
});

require __DIR__.'/auth.php';
