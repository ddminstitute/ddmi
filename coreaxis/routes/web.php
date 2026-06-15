<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

// Public Website Routes
Route::get('/', [WebsiteController::class, 'home'])->name('home');
Route::get('/about', [WebsiteController::class, 'about'])->name('about');
Route::get('/services', [WebsiteController::class, 'services'])->name('services');
Route::get('/account-plans', [WebsiteController::class, 'accountPlans'])->name('account.plans');
Route::get('/contact', [WebsiteController::class, 'contact'])->name('contact');
Route::post('/contact', [WebsiteController::class, 'sendContact'])->name('contact.send');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('accounts', AccountController::class);

    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::get('/action/deposit', [TransactionController::class, 'depositForm'])->name('deposit');
        Route::post('/action/deposit', [TransactionController::class, 'deposit'])->name('deposit.post');
        Route::get('/action/withdraw', [TransactionController::class, 'withdrawForm'])->name('withdraw');
        Route::post('/action/withdraw', [TransactionController::class, 'withdraw'])->name('withdraw.post');
        Route::get('/action/transfer', [TransactionController::class, 'transferForm'])->name('transfer');
        Route::post('/action/transfer', [TransactionController::class, 'transfer'])->name('transfer.post');
        Route::get('/{transaction}', [TransactionController::class, 'show'])->name('show');
    });

    Route::resource('loans', LoanController::class)->except(['edit', 'update', 'destroy']);
    Route::post('/loans/{loan}/approve', [LoanController::class, 'approve'])->name('loans.approve');
    Route::post('/loans/{loan}/reject', [LoanController::class, 'reject'])->name('loans.reject');
    Route::post('/loans/{loan}/disburse', [LoanController::class, 'disburse'])->name('loans.disburse');
    Route::post('/loans/{loan}/payment', [LoanController::class, 'makePayment'])->name('loans.payment');

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/transactions', [ReportController::class, 'transactions'])->name('transactions');
        Route::get('/statement', [ReportController::class, 'statement'])->name('statement');
        Route::get('/loans', [ReportController::class, 'loans'])->name('loans');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
