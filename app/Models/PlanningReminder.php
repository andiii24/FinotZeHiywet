<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class PlanningReminder extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'planning_id',
        'planning_task_id',
        'title',
        'description',
        'reminder_date',
        'reminder_time',
        'reminder_type', // email, sms, push, in_app
        'is_sent',
        'sent_at',
        'created_by',
        'recipients', // JSON array of user IDs
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'reminder_date' => 'date',
        'reminder_time' => 'datetime',
        'recipients' => 'array',
        'is_sent' => 'boolean',
        'sent_at' => 'datetime',
    ];

    /**
     * Reminder type constants
     */
    const TYPE_EMAIL = 'email';
    const TYPE_SMS = 'sms';
    const TYPE_PUSH = 'push';
    const TYPE_IN_APP = 'in_app';

    /**
     * Get the planning that this reminder belongs to.
     */
    public function planning(): BelongsTo
    {
        return $this->belongsTo(Planning::class);
    }

    /**
     * Get the planning task that this reminder belongs to.
     */
    public function planningTask(): BelongsTo
    {
        return $this->belongsTo(PlanningTask::class);
    }

    /**
     * Get the user who created this reminder.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the reminder type options.
     */
    public static function getReminderTypes(): array
    {
        return [
            self::TYPE_EMAIL => 'Email',
            self::TYPE_SMS => 'SMS',
            self::TYPE_PUSH => 'Push Notification',
            self::TYPE_IN_APP => 'In-App Notification',
        ];
    }

    /**
     * Check if the reminder is due.
     */
    public function isDue(): bool
    {
        return $this->reminder_time <= Carbon::now() && !$this->is_sent;
    }

    /**
     * Mark the reminder as sent.
     */
    public function markAsSent(): void
    {
        $this->update([
            'is_sent' => true,
            'sent_at' => Carbon::now(),
        ]);
    }
}
