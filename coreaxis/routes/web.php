<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ChequeController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DemandDraftController;
use App\Http\Controllers\EmailStatementController;
use App\Http\Controllers\EodController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FixedDepositController;
use App\Http\Controllers\FundTransferController;
use App\Http\Controllers\GrievanceController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LoanEnhancedController;
use App\Http\Controllers\NomineeController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\RecurringDepositController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportEnhancedController;
use App\Http\Controllers\SavingPlanController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\StandingInstructionController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionPinController;
use App\Http\Controllers\TransactionReversalController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ── Auth ──────────────────────────────────────────────────────────────────────
require __DIR__.'/auth.php';

// ── Authenticated routes ───────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'session.timeout'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/', fn () => redirect()->route('dashboard'));

    // Transaction PIN
    Route::get('/pin/set',      [TransactionPinController::class, 'showSetPin'])->name('pin.set');
    Route::post('/pin/set',     [TransactionPinController::class, 'setPin']);
    Route::get('/pin/verify',   [TransactionPinController::class, 'showVerifyPin'])->name('pin.verify');
    Route::post('/pin/verify',  [TransactionPinController::class, 'verifyPin']);

    // Customers
    Route::resource('customers', CustomerController::class);

    // Accounts
    Route::resource('accounts', AccountController::class);
    // Nominees
    Route::resource('accounts.nominees', NomineeController::class)->shallow();
    // Email statement
    Route::post('accounts/{account}/email-statement', [EmailStatementController::class, 'send'])->name('accounts.email-statement');

    // Transactions
    Route::get('transactions',            [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('transactions/deposit',    [TransactionController::class, 'depositForm'])->name('transactions.deposit');
    Route::post('transactions/deposit',   [TransactionController::class, 'deposit']);
    Route::get('transactions/withdraw',   [TransactionController::class, 'withdrawForm'])->name('transactions.withdraw');
    Route::post('transactions/withdraw',  [TransactionController::class, 'withdraw']);
    Route::get('transactions/transfer',   [TransactionController::class, 'transferForm'])->name('transactions.transfer');
    Route::post('transactions/transfer',  [TransactionController::class, 'transfer']);
    Route::post('transactions/{transaction}/reverse', [TransactionReversalController::class, 'reverse'])->name('transactions.reverse');

    // Loans
    Route::resource('loans', LoanController::class);
    Route::post('loans/{loan}/approve',     [LoanController::class, 'approve'])->name('loans.approve');
    Route::post('loans/{loan}/reject',      [LoanController::class, 'reject'])->name('loans.reject');
    Route::post('loans/{loan}/disburse',    [LoanController::class, 'disburse'])->name('loans.disburse');
    Route::post('loans/{loan}/payment',     [LoanController::class, 'payment'])->name('loans.payment');
    // Loan Enhancements
    Route::get('loans/{loan}/guarantors',           [LoanEnhancedController::class, 'guarantors'])->name('loans.guarantors');
    Route::post('loans/{loan}/guarantors',          [LoanEnhancedController::class, 'addGuarantor'])->name('loans.add-guarantor');
    Route::delete('loans/{loan}/guarantors/{guarantor}', [LoanEnhancedController::class, 'removeGuarantor'])->name('loans.remove-guarantor');
    Route::get('loans/{loan}/collaterals',          [LoanEnhancedController::class, 'collaterals'])->name('loans.collaterals');
    Route::post('loans/{loan}/collaterals',         [LoanEnhancedController::class, 'addCollateral'])->name('loans.add-collateral');
    Route::get('loans/{loan}/foreclosure',          [LoanEnhancedController::class, 'foreclosure'])->name('loans.foreclosure');
    Route::post('loans/{loan}/foreclose',           [LoanEnhancedController::class, 'foreclose'])->name('loans.foreclose');
    Route::get('loans/{loan}/restructure',          [LoanEnhancedController::class, 'restructure'])->name('loans.restructure');
    Route::post('loans/{loan}/do-restructure',      [LoanEnhancedController::class, 'doRestructure'])->name('loans.do-restructure');

    // Collections
    Route::resource('collection-plans', CollectionController::class);
    Route::post('collection-plans/{plan}/collect', [CollectionController::class, 'collect'])->name('collection-plans.collect');

    // Saving Plans
    Route::resource('saving-plans', SavingPlanController::class);
    Route::post('saving-plans/{plan}/payment', [SavingPlanController::class, 'payment'])->name('saving-plans.payment');

    // Fixed Deposits
    Route::resource('fixed-deposits', FixedDepositController::class)->except(['edit','update','destroy']);
    Route::post('fixed-deposits/{fixedDeposit}/close', [FixedDepositController::class, 'close'])->name('fixed-deposits.close');

    // Recurring Deposits
    Route::resource('recurring-deposits', RecurringDepositController::class)->except(['edit','update','destroy']);
    Route::post('recurring-deposits/{recurringDeposit}/installments/{installment}/pay',
        [RecurringDepositController::class, 'payInstallment'])->name('recurring-deposits.pay-installment');

    // Cheques
    Route::resource('cheques', ChequeController::class)->except(['show','edit','update','destroy']);
    Route::post('cheques/{cheque}/update-status', [ChequeController::class, 'updateStatus'])->name('cheques.update-status');

    // Fund Transfers (NEFT/RTGS/IMPS)
    Route::resource('fund-transfers', FundTransferController::class)->except(['show','edit','update','destroy']);
    Route::post('fund-transfers/{fundTransfer}/update-status', [FundTransferController::class, 'updateStatus'])->name('fund-transfers.update-status');

    // Standing Instructions
    Route::resource('standing-instructions', StandingInstructionController::class)->except(['show','edit','update','destroy']);
    Route::post('standing-instructions/{si}/pause',  [StandingInstructionController::class, 'pause'])->name('standing-instructions.pause');
    Route::post('standing-instructions/{si}/cancel', [StandingInstructionController::class, 'cancel'])->name('standing-instructions.cancel');

    // Demand Drafts
    Route::resource('demand-drafts', DemandDraftController::class)->except(['show','edit','update','destroy']);
    Route::post('demand-drafts/{demandDraft}/cancel', [DemandDraftController::class, 'cancel'])->name('demand-drafts.cancel');

    // KYC
    Route::get('kyc',                 [KycController::class, 'index'])->name('kyc.index');
    Route::post('kyc/{customer}/verify', [KycController::class, 'verify'])->name('kyc.verify');

    // Grievances
    Route::resource('grievances', GrievanceController::class)->except(['edit','destroy']);

    // Service Requests
    Route::resource('service-requests', ServiceRequestController::class)->except(['show','edit','update','destroy']);
    Route::post('service-requests/{serviceRequest}/process', [ServiceRequestController::class, 'process'])->name('service-requests.process');

    // EOD
    Route::get('eod',         [EodController::class, 'index'])->name('eod.index');
    Route::post('eod/process',[EodController::class, 'process'])->name('eod.process');

    // Branches
    Route::resource('branches', BranchController::class)->except(['show','destroy']);

    // Audit Log
    Route::get('audit-log', [AuditLogController::class, 'index'])->name('audit-log.index');

    // Reports
    Route::get('reports/transactions', [ReportController::class, 'transactions'])->name('reports.transactions');
    Route::get('reports/statement',    [ReportController::class, 'statement'])->name('reports.statement');
    Route::get('reports/loans',        [ReportController::class, 'loans'])->name('reports.loans');
    Route::get('reports/npa',          [ReportEnhancedController::class, 'npa'])->name('reports.npa');
    Route::get('reports/cashflow',     [ReportEnhancedController::class, 'cashflow'])->name('reports.cashflow');
    Route::get('reports/regulatory',   [ReportEnhancedController::class, 'regulatory'])->name('reports.regulatory');

    // HR / Employees / Expenses
    Route::resource('employees', \App\Http\Controllers\EmployeeController::class);
    Route::post('employees/{employee}/payslip', [\App\Http\Controllers\EmployeeController::class, 'generatePayslip'])->name('employees.payslip');
    Route::resource('expenses', ExpenseController::class);

    // Users
    Route::resource('users', UserController::class);

    // Profile
    Route::get('profile',   [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile',[\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    // Print / PDF views
    Route::get('print/passbook/{account}',          [PrintController::class, 'passbook'])->name('print.passbook');
    Route::get('print/statement/{account}',         [PrintController::class, 'statement'])->name('print.statement');
    Route::get('print/receipt/{transaction}',       [PrintController::class, 'receipt'])->name('print.receipt');
    Route::get('print/loan-certificate/{loan}',     [PrintController::class, 'loanCertificate'])->name('print.loan.certificate');
    Route::get('print/collection-receipt/{collection}', [PrintController::class, 'collectionReceipt'])->name('print.collection.receipt');
    Route::get('print/payslip/{payslip}',           [PrintController::class, 'payslip'])->name('print.payslip');
    Route::get('print/fd-certificate/{fixedDeposit}', [\App\Http\Controllers\FixedDepositController::class, 'printCertificate'])->name('print.fd.certificate');
    Route::get('print/dd-receipt/{demandDraft}',    [\App\Http\Controllers\DemandDraftController::class, 'printReceipt'])->name('print.dd.receipt');

    // Super Admin
    Route::prefix('super-admin')->name('super-admin.')->middleware('can:super-admin')->group(function () {
        Route::get('dashboard',    [SuperAdminController::class, 'dashboard'])->name('dashboard');
        Route::get('permissions',  [SuperAdminController::class, 'permissions'])->name('permissions');
        Route::post('permissions', [SuperAdminController::class, 'updatePermissions'])->name('permissions.update');
    });
});
