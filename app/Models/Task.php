<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'status',
        'priority',
        'deadline',
        'user_id',
        'group_cat_id',
        'for_all',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'deadline' => 'date',
        'for_all' => 'boolean',
    ];

    /**
     * Get the user that owns the task.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the group category that the task belongs to.
     */
    public function groupCat(): BelongsTo
    {
        return $this->belongsTo(Group_cat::class, 'group_cat_id');
    }

    /**
     * Scope a query to only include tasks for a specific user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include tasks with a specific status.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include tasks with a specific priority.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $priority
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope a query to only include tasks for a specific group category.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $groupCatId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForGroupCat($query, $groupCatId)
    {
        return $query->where('group_cat_id', $groupCatId);
    }

    /**
     * Scope a query to only include tasks that are for all users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForAll($query)
    {
        return $query->where('for_all', true);
    }

    /**
     * Get the users that have this task with their individual completion status.
     */
    public function userTasks(): HasMany
    {
        return $this->hasMany(UserTask::class);
    }

    /**
     * Get all users that have this task assigned.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_tasks')
                    ->withPivot('status')
                    ->withTimestamps();
    }
}
