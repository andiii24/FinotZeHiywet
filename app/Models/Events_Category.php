<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Events_Category extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'event_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the events in this category.
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'event_category_id');
    }
}
