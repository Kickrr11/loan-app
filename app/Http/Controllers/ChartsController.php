<?php

namespace App\Http\Controllers;

use App\Enums\LoanAmountDistribution;
use App\Enums\LoansMonthlyAmountDistribution;
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
        $usersLoans = User::withCount('loans')->get(['name', 'loans_count']);

        $formattedUsersLoans = $usersLoans->map(function ($user) {
            return [
                'name' => $user->name,
                'y' => (float)$user->loans_count,
            ];
        })->reject(fn($user) => $user['y'] < 1)->values()->all();

        $loansAmountDistributions = LoanAmountDistribution::prepareLoansDistributionsAmountsData();
        $monthlyLoanDistributions = Loan::monthlyAmountDistribution();

        return Inertia::render('Dashboard', [
            'usersLoans' => $formattedUsersLoans,
            'loansAmountDistributions' => $loansAmountDistributions,
            'monthlyLoanDistributions' => $monthlyLoanDistributions,
        ]);
    }
}