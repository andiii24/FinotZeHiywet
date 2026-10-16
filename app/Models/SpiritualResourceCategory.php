<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SpiritualResourceCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function resources(): BelongsToMany
    {
        return $this->belongsToMany(
            SpiritualResource::class,
            'spiritual_resource_category',
            'spiritual_resource_category_id',
            'spiritual_resource_id'
        )->withTimestamps();
    }
}
