<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payments\StorePaymentRequest;
use App\Models\Loan;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;

class PaymentsController extends Controller
{
    /**
     * @param StorePaymentRequest $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function store(StorePaymentRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $amount = $request->get('amount');
            $loan = Loan::findOrFail(Arr::get($request->get('loan'), 'id'));
            Payment::create([
                'loan_id' => $loan->id,
                'amount' => $request->get('amount'),
            ]);
            $successMessage = "Payment of $amount BGL. for loan $loan->loan_code successfully made";
            if ($amount > $loan->remaining_amount) {
                $amountOverpaid = $amount - $loan->remaining_amount;
                $successMessage = "Loan successfully settled, $amountOverpaid has not been captured since the total amount due for $loan->load_code was $loan->remaining_amount";
            }

            $remainingAmount = max(0, $loan->remaining_amount - $amount);

            $loan->update(['remaining_amount' => $remainingAmount]);
            DB::commit();

            return to_route('dashboard', [
                'loans' => Loan::with('borrower')->paginate(10),
            ])->with('success', $successMessage);
        } catch (\Throwable $throwable) {
            DB::rollBack();
            Log::critical('Something went wrong while storing a new payment:' . $throwable->getMessage());
            return redirect()->back()->with('error',
                'Something went wrong while storing a new payment, please contact support!');
        }
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('Payments/Create', [
            'loans' => Loan::all(),
        ]);
    }
}