<?php

namespace App\Observers;

use App\Models\PlanningTask;
use Carbon\Carbon;

class PlanningTaskObserver
{
    /**
     * Handle the PlanningTask "created" event.
     */
    public function created(PlanningTask $planningTask): void
    {
        // Check if task should automatically start
        $this->checkAndUpdateTaskStatus($planningTask);
    }

    /**
     * Handle the PlanningTask "updated" event.
     */
    public function updated(PlanningTask $planningTask): void
    {
        // Check if task should automatically start after update
        $this->checkAndUpdateTaskStatus($planningTask);
    }

    /**
     * Handle the PlanningTask "deleted" event.
     */
    public function deleted(PlanningTask $planningTask): void
    {
        // Update planning progress when task is deleted
        $this->updatePlanningProgress($planningTask->planning);
    }

    /**
     * Handle the PlanningTask "restored" event.
     */
    public function restored(PlanningTask $planningTask): void
    {
        // Check if task should automatically start after restoration
        $this->checkAndUpdateTaskStatus($planningTask);
    }

    /**
     * Handle the PlanningTask "force deleted" event.
     */
    public function forceDeleted(PlanningTask $planningTask): void
    {
        // Update planning progress when task is force deleted
        $this->updatePlanningProgress($planningTask->planning);
    }

    /**
     * Check if task should automatically start and update status accordingly.
     */
    private function checkAndUpdateTaskStatus(PlanningTask $planningTask): void
    {
        $today = Carbon::now()->startOfDay();
        $startDate = Carbon::parse($planningTask->start_date)->startOfDay();
        $endDate = Carbon::parse($planningTask->end_date)->startOfDay();

        // Only update if task is not started and start date has been reached
        if ($planningTask->status === PlanningTask::STATUS_NOT_STARTED && $today->greaterThanOrEqualTo($startDate)) {
            $planningTask->update([
                'status' => PlanningTask::STATUS_IN_PROGRESS
            ]);

            // Log the automatic status change
            \Log::info("Task '{$planningTask->title}' automatically started on {$today->format('Y-m-d')}");
        }

        // Check if task should be marked as overdue
        if ($planningTask->status !== PlanningTask::STATUS_COMPLETED &&
            $planningTask->status !== PlanningTask::STATUS_CANCELLED &&
            $today->greaterThan($endDate)) {

            // Optionally mark as overdue (you can implement this logic)
            \Log::warning("Task '{$planningTask->title}' is overdue (end date: {$endDate->format('Y-m-d')})");
        }

        // Update planning progress
        $this->updatePlanningProgress($planningTask->planning);
    }

    /**
     * Update planning progress based on task completion.
     */
    private function updatePlanningProgress($planning): void
    {
        if (!$planning) return;

        $totalTasks = $planning->planningTasks()->count();
        if ($totalTasks > 0) {
            $completedTasks = $planning->planningTasks()->where('status', PlanningTask::STATUS_COMPLETED)->count();
            $progressPercentage = ($completedTasks / $totalTasks) * 100;

            $planning->update(['progress_percentage' => round($progressPercentage, 2)]);
        }
    }
}
