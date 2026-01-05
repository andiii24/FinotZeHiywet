<?php

namespace App\Console\Commands;

use App\Models\PlanningTask;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateTaskStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'planning:update-task-statuses {--dry-run : Show what would be updated without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update task statuses based on their start and end dates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $today = Carbon::now()->startOfDay();

        $this->info('🔄 Updating task statuses...');
        $this->info("📅 Current date: {$today->format('Y-m-d')}");

        if ($isDryRun) {
            $this->warn('🔍 DRY RUN MODE - No changes will be made');
        }

        // Find tasks that should start today
        $tasksToStart = PlanningTask::where('status', PlanningTask::STATUS_NOT_STARTED)
            ->whereDate('start_date', '<=', $today)
            ->get();

        // Find tasks that are overdue
        $overdueTasks = PlanningTask::whereIn('status', [
                PlanningTask::STATUS_NOT_STARTED,
                PlanningTask::STATUS_IN_PROGRESS
            ])
            ->whereDate('end_date', '<', $today)
            ->get();

        // Find tasks that should be completed (past end date but still in progress)
        $tasksToComplete = PlanningTask::where('status', PlanningTask::STATUS_IN_PROGRESS)
            ->whereDate('end_date', '<', $today)
            ->get();

        $this->info("\n📊 Status Update Summary:");
        $this->info("• Tasks to start: " . $tasksToStart->count());
        $this->info("• Overdue tasks: " . $overdueTasks->count());
        $this->info("• Tasks to complete: " . $tasksToComplete->count());

        if ($tasksToStart->count() > 0) {
            $this->info("\n🚀 Tasks that will be started:");
            foreach ($tasksToStart as $task) {
                $this->line("  • {$task->title} (Start: {$task->start_date->format('Y-m-d')})");

                if (!$isDryRun) {
                    $task->update(['status' => PlanningTask::STATUS_IN_PROGRESS]);
                    $this->info("    ✅ Status updated to 'In Progress'");
                }
            }
        }

        if ($overdueTasks->count() > 0) {
            $this->warn("\n⚠️  Overdue tasks:");
            foreach ($overdueTasks as $task) {
                $this->line("  • {$task->title} (End: {$task->end_date->format('Y-m-d')}) - Status: {$task->status}");
            }
        }

        if ($tasksToComplete->count() > 0) {
            $this->info("\n🏁 Tasks that should be completed:");
            foreach ($tasksToComplete as $task) {
                $this->line("  • {$task->title} (End: {$task->end_date->format('Y-m-d')})");

                if (!$isDryRun) {
                    $task->update(['status' => PlanningTask::STATUS_COMPLETED]);
                    $this->info("    ✅ Status updated to 'Completed'");
                }
            }
        }

        if ($tasksToStart->count() === 0 && $tasksToComplete->count() === 0) {
            $this->info("\n✅ No tasks need status updates today!");
        }

        if (!$isDryRun) {
            $this->info("\n🎉 Task status update completed!");
        } else {
            $this->info("\n💡 Run without --dry-run to apply changes");
        }

        return Command::SUCCESS;
    }
}
