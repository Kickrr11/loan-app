<?php

namespace Tests\Feature;

use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\Assert;
use Inertia\Testing\AssertableInertia;
use Symfony\Component\HttpFoundation\Response;
use Tests\Fixtures\BaseFixture;
use Tests\TestCase;

class LoansTest extends TestCase
{
    use BaseFixture, RefreshDatabase;

    protected User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->createUser();
    }

    public function test_can_load_loans(): void
    {
        Loan::factory(5)->create();

        $this->actingAs($this->user)->get('/')->assertStatus(Response::HTTP_OK)->assertInertia(fn (
            AssertableInertia $page
        ) => $page->has('loans.links', 3));

        $this->assertEquals(5, Loan::count());
    }

    public function test_create_loan()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($this->user)->post('/loans/store', [
            'user' => $user,
            'amount' => 5000,
            'period' => 12,
        ]);

        $response->assertSessionHas('success', "Loan of 5000 BGL. for $user->name for 12 months successfully created");

        $loanFirst = Loan::first();
        $annualInterestRate = 7.9;
        $monthlyInterestRate = ($annualInterestRate / 12) / 100;
        $monthlyFee =  (floatval(5000) * $monthlyInterestRate) / (1 - pow(1 + $monthlyInterestRate, -12));
        $this->assertEquals(1, Loan::count());
        $this->assertEquals(5000, $loanFirst->amount);
        $this->assertSame(number_format($monthlyFee), number_format($loanFirst->monthly_fee));
        $this->assertEquals(12, $loanFirst->term_months);
        $this->assertEquals('0000001', $loanFirst->loan_code);
    }

    public function test_it_cannot_create_loan_without_amount()
    {
        $user = User::factory()->create();
        $this->actingAs($this->user, 'sanctum')->postJson("/loans/store",
            [
                'user' => $user,
                'amount' => null,
                'period' => 12,
            ]
        )->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['amount' => 'The amount field is required.']);

        $this->assertEquals(0, Loan::count());
    }

    public function test_it_cannot_create_loan_without_period()
    {
        $user = User::factory()->create();
        $this->actingAs($this->user, 'sanctum')->postJson("/loans/store",
            [
                'user' => $user,
                'amount' => 15,
                'period' => null,
            ]
        )->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['period' => 'The period field is required.']);

        $this->assertEquals(0, Loan::count());
    }

    public function test_it_cannot_create_loan_when_period_is_invalid()
    {
        $user = User::factory()->create();
        $this->actingAs($this->user, 'sanctum')->postJson("/loans/store",
            [
                'user' => $user,
                'amount' => 15,
                'period' => 130,
            ]
        )->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['period' => 'The period field must be between 3 and 120.']);

        $this->assertEquals(0, Loan::count());
    }

    public function test_it_cannot_create_loan_for_user_when_total_loans_exceed_max_allowed_loan_amount()
    {
        $user = User::factory()->create();
        Loan::factory()->create(['borrower_id' => $user->id, 'amount' => 79990]);

        $this->actingAs($this->user, 'sanctum')->postJson("/loans/store",
            [
                'user' => $user,
                'amount' => 15,
                'period' => 12,
            ]
        )->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['amount' => 'The total amount of the user loans must be less than 80 000lv.']);

        $this->assertEquals(1, Loan::count());
    }
}
