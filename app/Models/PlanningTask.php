<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class PlanningTask extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'planning_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'priority_level',
        'status',
        'progress_percentage',
        'estimated_hours',
        'actual_hours',
        'dependencies', // JSON array of task IDs this task depends on
        'assigned_to',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'dependencies' => 'array',
        'progress_percentage' => 'decimal:2',
        'estimated_hours' => 'decimal:2',
        'actual_hours' => 'decimal:2',
    ];

    /**
     * Priority level constants
     */
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_CRITICAL = 'critical';

    /**
     * Status constants
     */
    const STATUS_NOT_STARTED = 'not_started';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_ON_HOLD = 'on_hold';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the planning that this task belongs to.
     */
    public function planning(): BelongsTo
    {
        return $this->belongsTo(Planning::class);
    }

    /**
     * Get the user assigned to this task.
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the user who created this task.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the task dependencies.
     */
    public function dependencyTasks(): BelongsToMany
    {
        return $this->belongsToMany(PlanningTask::class, 'planning_task_dependencies', 'task_id', 'dependency_id');
    }

    /**
     * Get tasks that depend on this task.
     */
    public function dependentTasks(): BelongsToMany
    {
        return $this->belongsToMany(PlanningTask::class, 'planning_task_dependencies', 'dependency_id', 'task_id');
    }

    /**
     * Check if the task is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->end_date < Carbon::now() && $this->status !== self::STATUS_COMPLETED;
    }

    /**
     * Check if the task is due soon (within 3 days).
     */
    public function isDueSoon(): bool
    {
        return $this->end_date <= Carbon::now()->addDays(3) &&
               $this->end_date > Carbon::now() &&
               $this->status !== self::STATUS_COMPLETED;
    }

    /**
     * Get the priority level options.
     */
    public static function getPriorityLevels(): array
    {
        return [
            self::PRIORITY_LOW => 'Low',
            self::PRIORITY_MEDIUM => 'Medium',
            self::PRIORITY_HIGH => 'High',
            self::PRIORITY_CRITICAL => 'Critical',
        ];
    }

    /**
     * Get the status options.
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_NOT_STARTED => 'Not Started',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_ON_HOLD => 'On Hold',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    /**
     * Check if the task should automatically start based on start date.
     */
    public function shouldAutoStart(): bool
    {
        return $this->status === self::STATUS_NOT_STARTED &&
               Carbon::now()->startOfDay()->greaterThanOrEqualTo($this->start_date);
    }

    /**
     * Check if the task should be marked as completed based on end date.
     */
    public function shouldAutoComplete(): bool
    {
        return $this->status === self::STATUS_IN_PROGRESS &&
               Carbon::now()->startOfDay()->greaterThan($this->end_date);
    }

    /**
     * Automatically update task status based on dates.
     */
    public function updateStatusBasedOnDates(): bool
    {
        $updated = false;

        if ($this->shouldAutoStart()) {
            $this->update(['status' => self::STATUS_IN_PROGRESS]);
            $updated = true;
            \Log::info("Task '{$this->title}' automatically started on " . Carbon::now()->format('Y-m-d'));
        } elseif ($this->shouldAutoComplete()) {
            $this->update(['status' => self::STATUS_COMPLETED]);
            $updated = true;
            \Log::info("Task '{$this->title}' automatically completed on " . Carbon::now()->format('Y-m-d'));
        }

        return $updated;
    }
}
