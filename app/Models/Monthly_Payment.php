<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Monthly_Payment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'monthly_payments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'month',
        'amount',
        'required_amount',
        'payment_method',
        'image',
        'status',
        'notes',
    ];

    /**
     * Get the user that owns the monthly payment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
