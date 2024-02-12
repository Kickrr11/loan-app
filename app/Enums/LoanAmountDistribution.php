<?php

namespace App\Enums;

use App\Models\Loan;

enum LoanAmountDistribution: string
{
    case RANGE_0_1000 = '0-1000';
    case RANGE_1001_5000 = '1001-5000';
    case RANGE_5001_10000 = '5001-10000';
    case RANGE_10001_50000 = '10001-50000';
    case RANGE_OVER_50001 = '50001+';

    /**
     * @return int[]
     */
    public static function prepareLoansDistributionsAmountsData(): array
    {
        $loansAmountDistributions = [
            '0-1000' => 0,
            '1001-5000' => 0,
            '5001-10000' => 0,
            '10001-50000' => 0,
            '50001+' => 0,
        ];

        foreach (Loan::pluck('amount') as $amount) {
            match (true) {
                $amount <= 1000 => $loansAmountDistributions[self::RANGE_0_1000->value]++,
                $amount <= 5000 => $loansAmountDistributions[self::RANGE_1001_5000->value]++,
                $amount <= 10000 => $loansAmountDistributions[self::RANGE_5001_10000->value]++,
                $amount <= 50000 => $loansAmountDistributions[self::RANGE_10001_50000->value]++,
                $amount >= 50001 => $loansAmountDistributions[self::RANGE_OVER_50001->value]++,
            };
        }

        return $loansAmountDistributions;
    }
}