<?php

namespace Database\Factories;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Loan>
 */
class LoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $amount = $this->faker->numberBetween(10000, 20000);
        $monthlyInterestRate = (7.9 / 12) / 100;
        $period = $this->faker->numberBetween(3, 120);
        $monthlyFee = (floatval($amount) * $monthlyInterestRate) / (1 - pow(1 + $monthlyInterestRate, -$period));

        return [
            'borrower_id' => User::factory()->create()->id,
            'creator_id' => User::factory()->create()->id,
            'loan_code' => $this->faker->unique()->regexify('[0-9]{7}'),
            'amount' => $amount,
            'interest_rate' => 7.9,
            'monthly_fee' => $monthlyFee,
            'term_months' => $period,
            'remaining_amount' => $amount,
        ];
    }
}
