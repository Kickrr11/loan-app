<?php

namespace App\Http\Controllers;

use App\Enums\LoanAmountDistribution;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ChartsController extends Controller
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        $userLoans = $this->prepareUserLoans();
        $loansAmountDistributions = LoanAmountDistribution::prepareLoansDistributionsAmountsData();
        $monthlyLoanDistributions = Loan::monthlyAmountDistribution();
        $loansByPeriodAnalisys = $this->prepareLoansByPeriodsAnalisys();

        return Inertia::render('Dashboard', [
            'usersLoans' => $userLoans,
            'loansAmountDistributions' => $loansAmountDistributions,
            'monthlyLoanDistributions' => $monthlyLoanDistributions,
            'loansByPeriodAnalisys' => $loansByPeriodAnalisys,
        ]);
    }

    /**
     * @return array
     */
    private function prepareLoansByPeriodsAnalisys(): array
    {
        $loansByPeriod = Loan::prepareLoanTermAnalysys();
        $result = [];

        foreach ($loansByPeriod as $key => $value) {
            if ($value == 0) {
                continue;
            }
            $result[] = ['name' => $key, 'y' => intval($value)];
        }

        return $result;
    }

    /**
     * @return mixed
     */
    private function prepareUserLoans(): mixed
    {
        $usersLoans = User::withCount('loans')->get(['name', 'loans_count']);

        return $usersLoans->map(function ($user) {
            return [
                'name' => $user->name,
                'y' => (float)$user->loans_count,
            ];
        })->reject(fn($user) => $user['y'] < 1)->values()->all();
    }
}