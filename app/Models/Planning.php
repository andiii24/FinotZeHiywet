<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class Planning extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'title',
        'description',
        'objectives',
        'timeframe_type', // yearly, quarterly, monthly
        'start_date',
        'end_date',
        'priority_level', // low, medium, high, critical
        'group_cat_id',
        'group_list', // JSON array of group IDs
        'budget_amount',
        'status', // planning, active, completed, cancelled
        'created_by',
        'progress_percentage',
        'is_public',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'group_list' => 'array',
        'budget_amount' => 'decimal:2',
        'progress_percentage' => 'decimal:2',
        'is_public' => 'boolean',
    ];

    /**
     * Priority level constants
     */
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_CRITICAL = 'critical';

    /**
     * Timeframe type constants
     */
    const TIMEFRAME_YEARLY = 'yearly';
    const TIMEFRAME_QUARTERLY = 'quarterly';
    const TIMEFRAME_MONTHLY = 'monthly';

    /**
     * Status constants
     */
    const STATUS_PLANNING = 'planning';
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the group category that this planning belongs to.
     */
    public function groupCat(): BelongsTo
    {
        return $this->belongsTo(Group_cat::class, 'group_cat_id');
    }

    /**
     * Get the user who created this planning.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the planning tasks.
     */
    public function planningTasks(): HasMany
    {
        return $this->hasMany(PlanningTask::class);
    }

    /**
     * Get the planning reminders.
     */
    public function reminders(): HasMany
    {
        return $this->hasMany(PlanningReminder::class);
    }

    /**
     * Get the planning budget records.
     */
    public function budgetRecords(): HasMany
    {
        return $this->hasMany(PlanningBudget::class);
    }

    /**
     * Get users assigned to this planning.
     */
    public function assignedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'planning_users')
                    ->withPivot('role', 'assigned_at')
                    ->withTimestamps();
    }

    /**
     * Check if the planning is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->end_date < Carbon::now() && $this->status !== self::STATUS_COMPLETED;
    }

    /**
     * Check if the planning is upcoming (starts within 7 days).
     */
    public function isUpcoming(): bool
    {
        return $this->start_date <= Carbon::now()->addDays(7) &&
               $this->start_date > Carbon::now() &&
               $this->status === self::STATUS_PLANNING;
    }

    /**
     * Get the total budget spent (expenses only).
     */
    public function getTotalSpentAttribute(): float
    {
        return $this->budgetRecords()->where('budget_type', 'expense')->sum('amount');
    }

    /**
     * Get the total budget income.
     */
    public function getTotalIncomeAttribute(): float
    {
        return $this->budgetRecords()->where('budget_type', 'income')->sum('amount');
    }

    /**
     * Get the remaining budget (budget_amount + income - expenses).
     */
    public function getRemainingBudgetAttribute(): float
    {
        return $this->budget_amount + $this->total_income - $this->total_spent;
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
     * Get the timeframe type options.
     */
    public static function getTimeframeTypes(): array
    {
        return [
            self::TIMEFRAME_YEARLY => 'Yearly',
            self::TIMEFRAME_QUARTERLY => 'Quarterly',
            self::TIMEFRAME_MONTHLY => 'Monthly',
        ];
    }

    /**
     * Get the status options.
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_PLANNING => 'Planning',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }
}
