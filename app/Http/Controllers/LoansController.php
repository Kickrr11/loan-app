<?php

namespace App\Http\Controllers;

use App\Http\Requests\Loans\StoreLoanRequest;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class LoansController extends Controller
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('Loans/Index', [
            'loans' => Loan::with('borrower')->paginate(10),
        ]);
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        for ($i=3; $i<=120; $i++) {
            $periods[] = $i;
        }

        return Inertia::render('Loans/Create', [
            'users' => User::all(),
            'periods' => $periods,
        ]);
    }

    /**
     * @param StoreLoanRequest $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function store(StoreLoanRequest $request): RedirectResponse
    {
        try {
            $borrower = User::findOrFail(Arr::get($request->get('user'), 'id'));
            $creatorId = auth()->user()->id;
            $amount = $request->get('amount');
            $latestLoan = Loan::latest()->first();
            $loanCode = $latestLoan ? str_pad($latestLoan->id + 1, 7, '0', STR_PAD_LEFT) : '0000001';
            $period = $request->get('period');
            $monthlyFee = $this->calculateMonthlyFee($amount, $period);

            Loan::create([
                'borrower_id' => $borrower?->id,
                'creator_id'  => $creatorId,
                'loan_code'   => $loanCode,
                'amount'    => $amount,
                'term_months' => $period,
                'remaining_amount' => $amount,
                'monthly_fee'  => $monthlyFee,
            ]);

            return to_route('dashboard', [
                'loans' => Loan::with('borrower')->paginate(10),
            ])->with('success', "Loan of $amount BGL. for $borrower->name for $period months successfully created");
        } catch (\Throwable $throwable) {
            Log::critical('Something went wrong while storing a new loan:' . $throwable->getMessage());
            return redirect()->back()->with('error',  'Something went wrong while storing a new loan, please contact support!');
        }
    }

    /**
     * @param $amount
     * @param $period
     * @return float
     */
    private function calculateMonthlyFee($amount, $period): float
    {
        $annualInterestRate = 7.9;
        $monthlyInterestRate = ($annualInterestRate / 12) / 100;
        return (floatval($amount) * $monthlyInterestRate) / (1 - pow(1 + $monthlyInterestRate, -$period));
    }
}