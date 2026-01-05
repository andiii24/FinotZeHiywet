<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Social_Contribution extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'social_contributions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'social_contribution_category_id',
        'title',
        'description',
        'target_amount',
        'single_amount',
        'date',
        'location',
        'type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'target_amount' => 'decimal:2',
        'single_amount' => 'decimal:2',
    ];

    /**
     * Get the category that owns the social contribution.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Social_Contribution_Category::class, 'social_contribution_category_id');
    }

    /**
     * Get the users who contributed to this social contribution.
     */
    public function users(): BelongsToMany
    {
        // Explicitly define pivot keys to avoid double-underscore issue
        return $this->belongsToMany(
            User::class,
            'social_contributors',
            'social_contribution_id', // this model's key on pivot
            'user_id'                  // related model's key on pivot
        )
        ->withPivot('amount', 'image', 'note')
        ->withTimestamps();
    }

    /**
     * Get all the contributor records for this social contribution.
     */
    public function contributors(): HasMany
    {
        // Explicit foreign key to match migration column name
        return $this->hasMany(Social_Contributors::class, 'social_contribution_id');
    }
}
