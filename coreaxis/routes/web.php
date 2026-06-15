<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\SavingPlanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\PrintController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WebsiteController::class, 'home'])->name('home');
Route::get('/about', [WebsiteController::class, 'about'])->name('about');
Route::get('/services', [WebsiteController::class, 'services'])->name('services');
Route::get('/account-plans', [WebsiteController::class, 'accountPlans'])->name('account.plans');
Route::get('/contact', [WebsiteController::class, 'contact'])->name('contact');
Route::post('/contact', [WebsiteController::class, 'sendContact'])->name('contact.send');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('customers', CustomerController::class);
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
    Route::get('/loans/{loan}/emi', [LoanController::class, 'emiSchedule'])->name('loans.emi');
    Route::post('/loans/{loan}/emi/{emi}/pay', [LoanController::class, 'payEmi'])->name('loans.pay-emi');

    Route::resource('collection-plans', CollectionController::class);
    Route::post('collection-plans/{collectionPlan}/entry', [CollectionController::class, 'addEntry'])->name('collection-plans.add-entry');

    Route::resource('saving-plans', SavingPlanController::class);

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/transactions', [ReportController::class, 'transactions'])->name('transactions');
        Route::get('/statement', [ReportController::class, 'statement'])->name('statement');
        Route::get('/loans', [ReportController::class, 'loans'])->name('loans');
    });

    Route::prefix('print')->name('print.')->group(function () {
        Route::get('/passbook/{account}', [PrintController::class, 'passbook'])->name('passbook');
        Route::get('/statement/{account}', [PrintController::class, 'statement'])->name('statement');
        Route::get('/receipt/{transaction}', [PrintController::class, 'receipt'])->name('receipt');
        Route::get('/loan-certificate/{loan}', [PrintController::class, 'loanCertificate'])->name('loan.certificate');
        Route::get('/collection-receipt/{collectionEntry}', [PrintController::class, 'collectionReceipt'])->name('collection.receipt');
        Route::get('/payslip/{payslip}', [PrintController::class, 'payslip'])->name('payslip');
    });

    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('employees', EmployeeController::class);
    Route::get('employees/{employee}/attendance/{year?}/{month?}', [EmployeeController::class, 'attendance'])->name('employees.attendance');
    Route::post('employees/{employee}/attendance', [EmployeeController::class, 'markAttendance'])->name('employees.attendance.store');
    Route::post('employees/{employee}/payslip', [EmployeeController::class, 'generatePayslip'])->name('employees.payslip.generate');

    Route::resource('expenses', ExpenseController::class)->except(['show']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('super_admin')->prefix('super-admin')->name('super-admin.')->group(function () {
        Route::get('dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
        Route::get('permissions', [SuperAdminController::class, 'permissions'])->name('permissions');
        Route::post('permissions', [SuperAdminController::class, 'updatePermissions'])->name('permissions.update');
        Route::get('seed', [SuperAdminController::class, 'seedSuperAdmin'])->name('seed');
    });
});

require __DIR__.'/auth.php';
