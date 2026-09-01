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

// ── PHASE 2 BANKING GAPS ──────────────────────────────────────────────

use App\Http\Controllers\FixedDepositController;
use App\Http\Controllers\RecurringDepositController;
use App\Http\Controllers\NomineeController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\ChequeController;
use App\Http\Controllers\FundTransferController;
use App\Http\Controllers\StandingInstructionController;
use App\Http\Controllers\LoanEnhancedController;
use App\Http\Controllers\GrievanceController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\DemandDraftController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\EodController;
use App\Http\Controllers\ReportEnhancedController;
use App\Http\Controllers\TransactionReversalController;

Route::middleware('auth')->group(function () {

    // ── Fixed Deposits ──────────────────────────────────────────────────────
    Route::resource('fixed-deposits', FixedDepositController::class)->except(['edit','update','destroy']);
    Route::post('fixed-deposits/{fixedDeposit}/close', [FixedDepositController::class, 'close'])->name('fixed-deposits.close');

    // ── Recurring Deposits ──────────────────────────────────────────────
    Route::resource('recurring-deposits', RecurringDepositController::class)->except(['edit','update','destroy']);
    Route::post('recurring-deposits/{recurringDeposit}/installments/{installment}/pay',
        [RecurringDepositController::class, 'payInstallment'])->name('recurring-deposits.pay-installment');

    // ── Nominees ───────────────────────────────────────────────────────────
    Route::get('accounts/{account}/nominees', [NomineeController::class, 'index'])->name('accounts.nominees.index');
    Route::get('accounts/{account}/nominees/create', [NomineeController::class, 'create'])->name('accounts.nominees.create');
    Route::post('accounts/{account}/nominees', [NomineeController::class, 'store'])->name('accounts.nominees.store');
    Route::delete('accounts/{account}/nominees/{nominee}', [NomineeController::class, 'destroy'])->name('accounts.nominees.destroy');

    // ── KYC ─────────────────────────────────────────────────────────────────
    Route::get('kyc', [KycController::class, 'index'])->name('kyc.index');
    Route::post('kyc/{customer}/verify', [KycController::class, 'verify'])->name('kyc.verify');

    // ── Cheques ───────────────────────────────────────────────────────────
    Route::resource('cheques', ChequeController::class)->except(['edit','update','destroy']);
    Route::post('cheques/{cheque}/status', [ChequeController::class, 'updateStatus'])->name('cheques.update-status');

    // ── Fund Transfers (NEFT/RTGS/IMPS) ───────────────────────────────────
    Route::resource('fund-transfers', FundTransferController::class)->except(['edit','update','destroy']);
    Route::post('fund-transfers/{fundTransfer}/status', [FundTransferController::class, 'updateStatus'])->name('fund-transfers.update-status');

    // ── Standing Instructions ──────────────────────────────────────────────
    Route::resource('standing-instructions', StandingInstructionController::class)->except(['edit','update','destroy']);
    Route::post('standing-instructions/{standingInstruction}/pause', [StandingInstructionController::class, 'pause'])->name('standing-instructions.pause');
    Route::post('standing-instructions/{standingInstruction}/cancel', [StandingInstructionController::class, 'cancel'])->name('standing-instructions.cancel');

    // ── Loan Enhancements ──────────────────────────────────────────────
    Route::get('loans/{loan}/guarantors', [LoanEnhancedController::class, 'guarantors'])->name('loans.guarantors');
    Route::post('loans/{loan}/guarantors', [LoanEnhancedController::class, 'addGuarantor'])->name('loans.add-guarantor');
    Route::delete('loans/{loan}/guarantors/{guarantor}', [LoanEnhancedController::class, 'removeGuarantor'])->name('loans.remove-guarantor');
    Route::get('loans/{loan}/collaterals', [LoanEnhancedController::class, 'collaterals'])->name('loans.collaterals');
    Route::post('loans/{loan}/collaterals', [LoanEnhancedController::class, 'addCollateral'])->name('loans.add-collateral');
    Route::get('loans/{loan}/foreclosure', [LoanEnhancedController::class, 'foreclosureForm'])->name('loans.foreclosure');
    Route::post('loans/{loan}/foreclose', [LoanEnhancedController::class, 'foreclose'])->name('loans.foreclose');
    Route::get('loans/{loan}/restructure', [LoanEnhancedController::class, 'restructureForm'])->name('loans.restructure');
    Route::post('loans/{loan}/restructure', [LoanEnhancedController::class, 'restructure'])->name('loans.do-restructure');

    // ── Transaction Reversal ─────────────────────────────────────────────
    Route::post('transactions/{transaction}/reverse', [TransactionReversalController::class, 'reverse'])->name('transactions.reverse');

    // ── Grievances ──────────────────────────────────────────────────────────────
    Route::resource('grievances', GrievanceController::class)->except(['edit','update','destroy']);
    Route::patch('grievances/{grievance}', [GrievanceController::class, 'update'])->name('grievances.update');

    // ── Service Requests ─────────────────────────────────────────────────
    Route::resource('service-requests', ServiceRequestController::class)->except(['edit','update','destroy']);
    Route::post('service-requests/{serviceRequest}/process', [ServiceRequestController::class, 'process'])->name('service-requests.process');

    // ── Demand Drafts ─────────────────────────────────────────────────────────
    Route::resource('demand-drafts', DemandDraftController::class)->except(['edit','update','destroy']);
    Route::post('demand-drafts/{demandDraft}/cancel', [DemandDraftController::class, 'cancel'])->name('demand-drafts.cancel');

    // ── Branches ───────────────────────────────────────────────────────────────
    Route::resource('branches', BranchController::class)->except(['show','destroy']);

    // ── EOD ──────────────────────────────────────────────────────────────────
    Route::get('eod', [EodController::class, 'index'])->name('eod.index');
    Route::post('eod/process', [EodController::class, 'process'])->name('eod.process');

    // ── Enhanced Reports ─────────────────────────────────────────────────
    Route::get('reports/npa', [ReportEnhancedController::class, 'npa'])->name('reports.npa');
    Route::get('reports/cashflow', [ReportEnhancedController::class, 'cashflow'])->name('reports.cashflow');
    Route::get('reports/regulatory', [ReportEnhancedController::class, 'regulatory'])->name('reports.regulatory');

    // ── Audit Log ─────────────────────────────────────────────────────────────
    Route::get('audit-log', function() {
        $logs = \App\Models\ActivityLog::with('user')->latest()->paginate(50);
        return view('audit-log.index', compact('logs'));
    })->name('audit-log.index');

});
