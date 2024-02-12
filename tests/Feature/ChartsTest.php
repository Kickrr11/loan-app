<?php

namespace Tests\Feature;

use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia;
use Tests\Fixtures\BaseFixture;
use Tests\TestCase;

class ChartsTest extends TestCase
{
    use BaseFixture, RefreshDatabase;

    protected User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->createUser();
    }

    public function test_user_loans()
    {
        $firstUser = User::factory()->create();
        $secondUser = User::factory()->create();
        $thirdUser = User::factory()->create();

        Loan::factory()->count(3)->for($firstUser, 'borrower')->create();
        Loan::factory()->count(4)->for($secondUser, 'borrower')->create();
        Loan::factory()->count(5)->for($thirdUser, 'borrower')->create();

        $this->actingAs($this->user, 'sanctum')->get('charts')->assertInertia(
            fn(AssertableInertia $page) => $page
                ->has('usersLoans', 3)
                ->where('usersLoans.0.name', $firstUser->name)
                ->where('usersLoans.0.y', $firstUser->loans->count())
                ->where('usersLoans.1.name', $secondUser->name)
                ->where('usersLoans.1.y', $secondUser->loans->count())
        );
    }

    public function test_loans_amount_distribution()
    {
        Loan::factory()->count(11)->state(new Sequence(
            ['amount' => 300],
            ['amount' => 500],
            ['amount' => 500],
            ['amount' => 500],
            ['amount' => 3000],
            ['amount' => 4000],
            ['amount' => 4900],
            ['amount' => 5500],
            ['amount' => 8500],
            ['amount' => 15000],
            ['amount' => 100000],
        ))->create();

        $this->actingAs($this->user, 'sanctum')->get('charts')->assertInertia(
            fn(AssertableInertia $page
            ) => $page->has('loansAmountDistributions')
                ->where('loansAmountDistributions.0-1000', 4)
                ->where('loansAmountDistributions.1001-5000', 3)
                ->where('loansAmountDistributions.5001-10000', 2)
                ->where('loansAmountDistributions.10001-50000', 1)
                ->where('loansAmountDistributions.50001+', 1)
        );
    }

    public function test_monthly_loan_disbursement()
    {
        Loan::factory()->count(10)->state(new Sequence(
            ['amount' => 300, 'created_at' => Carbon::create(2023, 5)],
            ['amount' => 500, 'created_at' => Carbon::create(2023, 5)],
            ['amount' => 500, 'created_at' => Carbon::create(2023, 6)],
            ['amount' => 500, 'created_at' => Carbon::create(2023, 6)],
            ['amount' => 3000, 'created_at' => Carbon::create(2023, 7)],
            ['amount' => 4000, 'created_at' => Carbon::create(2023, 7)],
            ['amount' => 4900, 'created_at' => Carbon::create(2023, 8)],
            ['amount' => 5500, 'created_at' => Carbon::create(2023, 8)],
            ['amount' => 8500, 'created_at' => Carbon::create(2023, 9)],
            ['amount' => 15000, 'created_at' => Carbon::create(2023, 9)],
            ['amount' => 100000, 'created_at' => Carbon::create(2023, 10)],
        ))->create();

        $this->actingAs($this->user, 'sanctum')->get('charts')->assertInertia(fn(AssertableInertia $page
        ) => $page->has('monthlyLoanDistributions')
            ->where('monthlyLoanDistributions.0.month', 'May')
            ->where('monthlyLoanDistributions.0.amount', 800)
            ->where('monthlyLoanDistributions.1.month', 'Jun')
            ->where('monthlyLoanDistributions.1.amount', 1000)
        );
    }
 }