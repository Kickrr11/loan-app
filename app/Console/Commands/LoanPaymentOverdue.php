<?php

namespace App\Console\Commands;

use App\Models\Loan;
use App\Notifications\PaymentDueNotification;
use Illuminate\Console\Command;

class LoanPaymentOverdue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:loan-payment-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends email notification on payment overdue';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Loan::where(function ($query) {
            $query->whereBetween('payment_due_date', [now()->addWeek()->startOfDay(), now()->addWeek()->endOfDay()])
                ->orWhereBetween('payment_due_date', [now()->addDays(3)->startOfDay(), now()->addDays(3)->endOfDay()])
                ->orWhereBetween('payment_due_date',[now()->addDay()->startOfDay(), now()->addDay()->endOfDay()])
                ->orWhere('payment_due_date', '<=', now());
        })->orWhereBetween('payment_due_date', [now()->subDay()->startOfDay(), now()->subDay()->endOfDay()])->each(function ($loan) {
            $borrower = $loan->borrower;
            $borrower->notify(new PaymentDueNotification($loan, $loan->payment_due_date < now()));
        });
    }
}