<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Job extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'job_listings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'description',
        'vacancy',
        'status',
        'job_category_id',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'vacancy' => 'integer',
    ];

    /**
     * Get the category that owns the job.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Job_Category::class, 'job_category_id');
    }

    /**
     * Get the user who created the job.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
