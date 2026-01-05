<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanningBudget extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'planning_id',
        'category',
        'description',
        'amount',
        'budget_type', // income, expense
        'date',
        'created_by',
        'receipt_image',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    /**
     * Budget type constants
     */
    const TYPE_INCOME = 'income';
    const TYPE_EXPENSE = 'expense';

    /**
     * Get the planning that this budget record belongs to.
     */
    public function planning(): BelongsTo
    {
        return $this->belongsTo(Planning::class);
    }

    /**
     * Get the user who created this budget record.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the budget type options.
     */
    public static function getBudgetTypes(): array
    {
        return [
            self::TYPE_INCOME => 'Income',
            self::TYPE_EXPENSE => 'Expense',
        ];
    }

    /**
     * Scope for income records.
     */
    public function scopeIncome($query)
    {
        return $query->where('budget_type', self::TYPE_INCOME);
    }

    /**
     * Scope for expense records.
     */
    public function scopeExpense($query)
    {
        return $query->where('budget_type', self::TYPE_EXPENSE);
    }
}
