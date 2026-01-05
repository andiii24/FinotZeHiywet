<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * User role constants
     */
    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'group_cat_id',
        'marital_status',
        'education_background',
        'work_status',
        'job_title',
        'work_place',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'work_status' => 'boolean',
        ];
    }

    /**
     * Get the group category that the user belongs to.
     */
    public function groupCat(): BelongsTo
    {
        return $this->belongsTo(Group_cat::class, 'group_cat_id');
    }

    /**
     * Get the skills that belong to the user.
     */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'user_skills')
            ->withPivot('proficiency_level')
            ->withTimestamps();
    }

    /**
     * Check if the user is an admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if the user is a regular user.
     *
     * @return bool
     */
    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    /**
     * Get the monthly payments for the user.
     */
    public function monthlyPayments(): HasMany
    {
        return $this->hasMany(Monthly_Payment::class);
    }

    /**
     * Get the social contributions that the user has contributed to.
     */
    public function socialContributions(): BelongsToMany
    {
        return $this->belongsToMany(Social_Contribution::class, 'social_contributors')
                    ->withPivot('amount', 'image', 'note')
                    ->withTimestamps();
    }

    /**
     * Get all the contribution records for this user.
     */
    public function contributions(): HasMany
    {
        return $this->hasMany(Social_Contributors::class);
    }

    /**
     * Get all the tasks directly assigned to this user.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get all the user task status records.
     */
    public function userTasks(): HasMany
    {
        return $this->hasMany(UserTask::class);
    }

    /**
     * Get all tasks assigned to this user through the user_tasks table.
     */
    public function assignedTasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'user_tasks')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    /**
     * Get plannings created by this user.
     */
    public function plannings(): HasMany
    {
        return $this->hasMany(Planning::class, 'created_by');
    }

    /**
     * Get plannings assigned to this user.
     */
    public function assignedPlannings(): BelongsToMany
    {
        return $this->belongsToMany(Planning::class, 'planning_users')
                    ->withPivot('role', 'assigned_at')
                    ->withTimestamps();
    }

    /**
     * Get planning tasks assigned to this user.
     */
    public function planningTasks(): HasMany
    {
        return $this->hasMany(PlanningTask::class, 'assigned_to');
    }

    /**
     * Get planning reminders created by this user.
     */
    public function planningReminders(): HasMany
    {
        return $this->hasMany(PlanningReminder::class, 'created_by');
    }

    /**
     * Get planning budget records created by this user.
     */
    public function planningBudgets(): HasMany
    {
        return $this->hasMany(PlanningBudget::class, 'created_by');
    }
}
