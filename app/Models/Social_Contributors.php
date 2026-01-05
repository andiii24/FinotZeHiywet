<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Social_Contributors extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'social_contributors';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'social_contribution_id',
        'user_id',
        'amount',
        'image',
        'note',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Get the user that made this contribution.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the social contribution this record belongs to.
     */
    public function socialContribution(): BelongsTo
    {
        return $this->belongsTo(Social_Contribution::class, 'social_contribution_id');
    }
}
