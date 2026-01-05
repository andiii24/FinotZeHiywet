<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTask extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'task_id',
        'status',
    ];

    /**
     * Get the user that owns this task status.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the task that this status belongs to.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
