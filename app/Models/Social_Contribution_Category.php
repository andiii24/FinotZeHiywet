<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Social_Contribution_Category extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'social_contribution_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the social contributions for this category.
     */
    public function socialContributions(): HasMany
    {
        return $this->hasMany(Social_Contribution::class, 'social_contribution_category_id');
    }
}
