<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the users that have this skill.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_skills')
            ->withPivot('proficiency_level')
            ->withTimestamps();
    }
}
