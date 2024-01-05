<?php

namespace Tests\Feature;

use App\Models\Loan;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\Fixtures\BaseFixture;
use Tests\TestCase;

class PaymentsTest extends TestCase
{
    use BaseFixture, RefreshDatabase;

    protected User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->createUser();
    }

    public function test_create_payment()
    {
        $loan = Loan::factory()->create();
        $response = $this->actingAs($this->user)->post('/payments/store', [
            'loan' => $loan,
            'amount' => 500,
        ]);

        $response->assertSessionHas('success', "Payment of 500 BGL. for loan $loan->loan_code successfully made");
        $payment = Payment::first();
        $this->assertEquals(1, Payment::count());
        $this->assertEquals(500, $payment->amount);
        $this->assertEquals($loan->id, $payment->loan_id);
    }

    public function test_capture_only_loan_amount_due_when_payment_exceeds_amount_due()
    {
        $loan = Loan::factory()->create(['amount' => 500, 'remaining_amount' => 500]);
        $response = $this->actingAs($this->user)->post('/payments/store', [
            'loan' => $loan,
            'amount' => 1000,
        ]);

        $response->assertSessionHas('success', "Loan successfully settled, 500 has not been captured since the total amount due for  was 500");
        $payment = Payment::first();
        $loanFirst = Loan::first();
        $this->assertEquals(1, Payment::count());
        $this->assertEquals(0, $loanFirst->remaining_amount);
        $this->assertEquals($loan->id, $payment->loan_id);
    }

    public function test_it_cannot_create_payment_when_amount_is_wrong()
    {
        $loan = Loan::factory()->create(['amount' => 500, 'remaining_amount' => 500]);
        $this->actingAs($this->user, 'sanctum')->postJson("/payments/store",
            [
                'loan' => $loan,
                'amount' => 'test',
            ]
        )->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)->assertJsonValidationErrors(['amount' => 'The amount field must be an integer.']);

        $this->assertEquals(0, Payment::count());
    }
}
