<?php
namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Customer;
use App\Models\RecurringDeposit;
use App\Models\RdInstallment;
use App\Models\Transaction;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RecurringDepositController extends Controller
{
    public function index(Request $request)
    {
        $rds = RecurringDeposit::with('account.user','customer')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()->paginate(20)->withQueryString();
        return view('recurring-deposits.index', compact('rds'));
    }

    public function create()
    {
        $accounts  = Account::where('status','active')->with('user','customer')->get();
        $customers = Customer::where('status','active')->get();
        return view('recurring-deposits.create', compact('accounts','customers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'account_id'          => 'required|exists:accounts,id',
            'monthly_installment' => 'required|numeric|min:100',
            'interest_rate'       => 'required|numeric|min:0|max:20',
            'tenure_months'       => 'required|integer|min:6|max:120',
            'start_date'          => 'required|date',
        ]);

        $account      = Account::findOrFail($data['account_id']);
        $totalDeposit = $data['monthly_installment'] * $data['tenure_months'];
        $r            = $data['interest_rate'] / 100 / 12;
        $maturity     = $r > 0
            ? round($data['monthly_installment'] * ((pow(1+$r,$data['tenure_months'])-1)/$r) * (1+$r), 2)
            : $totalDeposit;
        $startDate    = Carbon::parse($data['start_date']);
        $maturityDate = $startDate->copy()->addMonths($data['tenure_months']);

        DB::transaction(function() use ($account, $data, $maturity, $maturityDate, $startDate) {
            $rd = RecurringDeposit::create([
                'rd_number'           => RecurringDeposit::generateRdNumber(),
                'customer_id'         => $account->customer_id,
                'account_id'          => $account->id,
                'monthly_installment' => $data['monthly_installment'],
                'interest_rate'       => $data['interest_rate'],
                'tenure_months'       => $data['tenure_months'],
                'start_date'          => $data['start_date'],
                'maturity_date'       => $maturityDate->toDateString(),
                'maturity_amount'     => $maturity,
                'next_due_date'       => $startDate->toDateString(),
                'created_by'          => auth()->id(),
            ]);

            for ($i = 1; $i <= $data['tenure_months']; $i++) {
                RdInstallment::create([
                    'rd_id'              => $rd->id,
                    'installment_number' => $i,
                    'due_date'           => $startDate->copy()->addMonths($i-1)->toDateString(),
                    'amount'             => $data['monthly_installment'],
                    'status'             => 'pending',
                ]);
            }
            ActivityLog::record('created', "RD {$rd->rd_number} opened", $rd);
        });

        return redirect()->route('recurring-deposits.index')->with('success','Recurring Deposit created successfully.');
    }

    public function show(RecurringDeposit $recurringDeposit)
    {
        $recurringDeposit->load('account.user','customer','installments');
        return view('recurring-deposits.show', compact('recurringDeposit'));
    }

    public function payInstallment(Request $request, RecurringDeposit $recurringDeposit, RdInstallment $installment)
    {
        if ($installment->status === 'paid') return back()->with('error','Already paid.');
        $account = $recurringDeposit->account;
        if ($account->balance < $installment->amount) return back()->with('error','Insufficient balance.');

        DB::transaction(function() use ($account, $recurringDeposit, $installment) {
            $before = $account->balance;
            $account->decrement('balance', $installment->amount);
            $ref = Transaction::generateReference();
            Transaction::create([
                'account_id'       => $account->id,
                'transaction_type' => 'withdrawal',
                'transaction_mode' => 'internal',
                'amount'           => $installment->amount,
                'balance_before'   => $before,
                'balance_after'    => $account->fresh()->balance,
                'description'      => "RD Installment #{$installment->installment_number} - {$recurringDeposit->rd_number}",
                'reference_number' => $ref,
                'status'           => 'completed',
            ]);
            $installment->update(['status' => 'paid','paid_date' => today()->toDateString(),'reference_number' => $ref]);

            $paid      = $recurringDeposit->installments()->where('status','paid')->count();
            $deposited = $recurringDeposit->monthly_installment * $paid;
            $next      = $recurringDeposit->installments()->where('status','pending')->orderBy('due_date')->first();
            $isComplete = $paid >= $recurringDeposit->tenure_months;

            $recurringDeposit->update([
                'installments_paid' => $paid,
                'total_deposited'   => $deposited,
                'status'            => $isComplete ? 'matured' : 'active',
                'next_due_date'     => $next?->due_date,
                'matured_at'        => $isComplete ? now() : null,
            ]);
        });

        return back()->with('success','RD installment paid successfully.');
    }
}
