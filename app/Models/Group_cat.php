<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group_cat extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the users that belong to this group category.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'group_cat_id');
    }
}
