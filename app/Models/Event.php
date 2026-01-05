<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'event_category_id',
        'name',
        'date',
        'description',
        'location',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'datetime',
    ];

    /**
     * Get the category that owns the event.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Events_Category::class, 'event_category_id');
    }

    /**
     * Get the user who created the event.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
