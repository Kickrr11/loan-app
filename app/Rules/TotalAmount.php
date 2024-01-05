<?php

namespace App\Rules;

use App\Models\Loan;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class TotalAmount implements ValidationRule
{
    /**
     * @param $borrowerId
     */
    public function __construct(public $borrowerId)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param \Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $totalLoanAmount = Loan::where('borrower_id', $this->borrowerId)->sum('amount');
        if($totalLoanAmount + $value >= 80001) {
            $fail('The total amount of the user loans must be less than 80 000lv.');
        }
    }
}
