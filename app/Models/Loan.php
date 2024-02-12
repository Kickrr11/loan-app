<?php

namespace App\Models;

use App\Enums\Code;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

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
        'payment_due_date',
    ];

    /**
     * @return BelongsTo
     */
    public function borrower(): BelongsTo
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }

    /**
     * @return mixed
     */
    public function scopeMonthlyAmountDistribution(): mixed
    {
        $monthNames = [
            '01' => 'Jan',
            '02' => 'Feb',
            '03' => 'Mar',
            '04' => 'Apr',
            '05' => 'May',
            '06' => 'Jun',
            '07' => 'Jul',
            '08' => 'Aug',
            '09' => 'Sep',
            '10' => 'Oct',
            '11' => 'Nov',
            '12' => 'Dec',
        ];

        $monthCase = collect($monthNames)
            ->map(function ($name, $month) {
                switch (config('database.default')) {
                    case 'mysql':
                        return "WHEN MONTH(created_at) = '$month' THEN '$name'";
                    case 'sqlite':
                        return "WHEN strftime('%m', created_at) = '$month' THEN '$name'";
                    default:
                        return '';
                }
            })
            ->implode(' ');

        $yearCase = config('database.default') === 'mysql' ? 'YEAR(created_at) as year' : "strftime('%Y', created_at) as year";
        $orderByMonthCase = config('database.default') === 'mysql' ? "MONTH('created_at')" : "strftime('%m', created_at)";

        $query = $this::select(
            DB::raw('sum(amount) as amount'),
            DB::raw('count(id) as loans_count'),
            DB::raw("CASE $monthCase END as month"),
            DB::raw($yearCase)
        )
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy(DB::raw($orderByMonthCase), 'asc');

        return $query->get();
    }

    /**
     * @return mixed
     */
    public function scopePrepareLoansDistributionsAmountsData(): mixed
    {
        return Loan::selectRaw('
                DATE_FORMAT(created_at, "%Y-%m") as month_year,
                SUM(CASE WHEN amount <= 1000 THEN 1 ELSE 0 END) as `0-1000`,
                SUM(CASE WHEN amount > 1000 AND amount <= 5000 THEN 1 ELSE 0 END) as `1001-5000`,
                SUM(CASE WHEN amount > 5000 AND amount <= 10000 THEN 1 ELSE 0 END) as `5001-10000`,
                SUM(CASE WHEN amount > 10000 AND amount <= 50000 THEN 1 ELSE 0 END) as `10001-50000`,
                SUM(CASE WHEN amount > 50000 THEN 1 ELSE 0 END) as `50001+`
            ')
            ->groupBy('month_year')
            ->get()->toArray();
    }

    /**
     * @return mixed
     */
    public function scopePrepareLoanTermAnalysys(): mixed
    {
        return Loan::selectRaw(
            'SUM(CASE WHEN term_months <= 12 THEN 1 ELSE 0 END) as short_term,
            SUM(CASE WHEN term_months > 12 AND term_months <=60 THEN 1 ELSE 0 END) as medium_term,
            SUM(CASE WHEN term_months >= 60  THEN 1 ELSE 0 END) as long_term',
        )->first()->toArray();
    }
}