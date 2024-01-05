<?php

use App\Http\Controllers\LoansController;
use App\Http\Controllers\PaymentsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/', [LoansController::class, 'index'])->name('dashboard');
    // Loans
    Route::get('loans/create', [LoansController::class, 'create'])->name('loan.create');
    Route::post('loans/store', [LoansController::class, 'store'])->name('loans.store');
    // Payments
    Route::get('payments/create', [PaymentsController::class, 'create'])->name('payments.create');
    Route::post('payments/store', [PaymentsController::class, 'store'])->name('payments.store');
});
