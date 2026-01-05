<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job_Category extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'job_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the jobs in this category.
     */
    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class, 'job_category_id');
    }
}
