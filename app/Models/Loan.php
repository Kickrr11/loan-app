<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrower_id',
        'creator_id',
        'amount',
        'term_months',
        'loan_code',
        'remaining_amount',
        'monthly_fee',
    ];

    /**
     * @return BelongsTo
     */
    public function borrower(): BelongsTo
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }
}
