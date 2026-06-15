<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Customer;
use App\Models\EmiSchedule;
use App\Models\Loan;
use App\Models\LoanPayment;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $query = Loan::with('user', 'account');
        if ($request->status) $query->where('status', $request->status);
        if ($request->type) $query->where('loan_type', $request->type);
        $loans = $query->latest()->paginate(15)->withQueryString();
        return view('loans.index', compact('loans'));
    }

    public function create()
    {
        $accounts = Account::where('status', 'active')->with('user','customer')->get();
        $customers = Customer::where('status','active')->get();
        return view('loans.create', compact('accounts','customers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'loan_type' => 'required|in:personal,home,auto,business',
            'principal_amount' => 'required|numeric|min:100',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'tenure_months' => 'required|integer|min:1|max:360',
            'purpose' => 'nullable|string|max:500',
        ]);

        $account = Account::findOrFail($data['account_id']);
        $emi = Loan::calculateEmi($data['principal_amount'], $data['interest_rate'], $data['tenure_months']);
        $total = round($emi * $data['tenure_months'], 2);

        Loan::create([
            'user_id' => $account->user_id,
            'customer_id' => $account->customer_id,
            'account_id' => $data['account_id'],
            'loan_number' => Loan::generateLoanNumber(),
            'loan_type' => $data['loan_type'],
            'amount' => $data['principal_amount'],
            'principal_amount' => $data['principal_amount'],
            'interest_rate' => $data['interest_rate'],
            'tenure_months' => $data['tenure_months'],
            'monthly_emi' => $emi,
            'emi_amount' => $emi,
            'total_amount' => $total,
            'outstanding_amount' => $total,
            'purpose' => $data['purpose'],
            'status' => 'pending',
        ]);

        return redirect()->route('loans.index')
            ->with('success', 'Loan application submitted successfully. Awaiting approval.');
    }

    public function show(Loan $loan)
    {
        $loan->load('user', 'customer', 'account', 'payments', 'emiSchedules');
        $schedule = $this->generateSchedule($loan);
        return view('loans.show', compact('loan', 'schedule'));
    }

    public function emiSchedule(Loan $loan)
    {
        $emiSchedules = $loan->emiSchedules()->get();
        return view('loans.emi-schedule', compact('loan', 'emiSchedules'));
    }

    public function payEmi(Loan $loan, EmiSchedule $emi)
    {
        if ($emi->status === 'paid') {
            return back()->with('error', 'This EMI is already paid.');
        }
        DB::transaction(function () use ($loan, $emi) {
            $emi->update(['status' => 'paid', 'paid_date' => now()->toDateString()]);
            $paid = $loan->emiSchedules()->where('status','paid')->sum('emi_amount');
            $outstanding = max(0, $loan->total_amount - $paid);
            $loan->update([
                'paid_amount' => $paid,
                'outstanding_amount' => $outstanding,
                'status' => $outstanding <= 0 ? 'closed' : 'active',
            ]);
        });
        return back()->with('success', 'EMI #'.$emi->installment_number.' marked as paid.');
    }

    public function approve(Loan $loan)
    {
        if ($loan->status !== 'pending') {
            return back()->with('error', 'Only pending loans can be approved.');
        }
        $loan->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        return back()->with('success', 'Loan approved successfully.');
    }

    public function reject(Loan $loan)
    {
        if ($loan->status !== 'pending') {
            return back()->with('error', 'Only pending loans can be rejected.');
        }
        $loan->update(['status' => 'rejected']);
        return back()->with('success', 'Loan rejected.');
    }

    public function disburse(Loan $loan)
    {
        if ($loan->status !== 'approved') {
            return back()->with('error', 'Only approved loans can be disbursed.');
        }

        DB::transaction(function () use ($loan) {
            $account = $loan->account;
            $before = $account->balance;
            $account->increment('balance', $loan->principal_amount);
            Transaction::create([
                'account_id' => $account->id,
                'transaction_type' => 'deposit',
                'amount' => $loan->principal_amount,
                'balance_before' => $before,
                'balance_after' => $account->fresh()->balance,
                'description' => "Loan disbursement - {$loan->loan_number}",
                'reference_number' => Transaction::generateReference(),
                'status' => 'completed',
            ]);
            $loan->update(['status' => 'active', 'disbursed_at' => now()]);
            // Generate EMI schedule
            $outstanding = $loan->principal_amount;
            $r = $loan->interest_rate / 12 / 100;
            $disbursedAt = Carbon::now();
            for ($i = 1; $i <= $loan->tenure_months; $i++) {
                $interest = round($outstanding * $r, 2);
                $principal = round($loan->emi_amount - $interest, 2);
                $outstanding = round($outstanding - $principal, 2);
                EmiSchedule::create([
                    'loan_id' => $loan->id,
                    'installment_number' => $i,
                    'due_date' => $disbursedAt->copy()->addMonths($i)->toDateString(),
                    'emi_amount' => $loan->emi_amount,
                    'principal_amount' => $principal,
                    'interest_amount' => $interest,
                    'balance_after' => max(0, $outstanding),
                    'status' => 'pending',
                ]);
            }
        });

        return back()->with('success', 'Loan disbursed successfully.');
    }

    public function makePayment(Request $request, Loan $loan)
    {
        if ($loan->status !== 'active') {
            return back()->with('error', 'Loan is not active.');
        }

        $data = $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $account = $loan->account;
        if ($account->balance < $data['amount']) {
            return back()->with('error', 'Insufficient balance in account.');
        }
        if ($data['amount'] > $loan->outstanding_amount) {
            return back()->with('error', 'Amount exceeds outstanding loan balance.');
        }

        DB::transaction(function () use ($loan, $account, $data) {
            $r = $loan->interest_rate / 12 / 100;
            $interestComponent = round($loan->outstanding_amount * $r, 2);
            $principalComponent = round($data['amount'] - $interestComponent, 2);
            $outstandingAfter = round($loan->outstanding_amount - $principalComponent, 2);

            $paymentNumber = $loan->payments()->count() + 1;
            LoanPayment::create([
                'loan_id' => $loan->id,
                'account_id' => $account->id,
                'payment_number' => $paymentNumber,
                'amount' => $data['amount'],
                'principal_component' => $principalComponent,
                'interest_component' => $interestComponent,
                'outstanding_after' => max(0, $outstandingAfter),
                'payment_date' => now()->toDateString(),
                'status' => 'paid',
                'reference_number' => Transaction::generateReference(),
            ]);

            $before = $account->balance;
            $account->decrement('balance', $data['amount']);
            Transaction::create([
                'account_id' => $account->id,
                'transaction_type' => 'withdrawal',
                'amount' => $data['amount'],
                'balance_before' => $before,
                'balance_after' => $account->fresh()->balance,
                'description' => "Loan EMI payment - {$loan->loan_number}",
                'reference_number' => Transaction::generateReference(),
                'status' => 'completed',
            ]);

            $newPaid = $loan->paid_amount + $data['amount'];
            $newOutstanding = max(0, $outstandingAfter);
            $loan->update([
                'paid_amount' => $newPaid,
                'outstanding_amount' => $newOutstanding,
                'status' => $newOutstanding <= 0 ? 'closed' : 'active',
            ]);
        });

        return back()->with('success', 'Loan payment recorded successfully.');
    }

    private function generateSchedule(Loan $loan): array
    {
        $schedule = [];
        $outstanding = $loan->principal_amount;
        $r = $loan->interest_rate / 12 / 100;
        for ($i = 1; $i <= $loan->tenure_months; $i++) {
            $interest = round($outstanding * $r, 2);
            $principal = round($loan->monthly_emi - $interest, 2);
            $outstanding = round($outstanding - $principal, 2);
            $schedule[] = [
                'month' => $i,
                'emi' => $loan->monthly_emi,
                'principal' => $principal,
                'interest' => $interest,
                'outstanding' => max(0, $outstanding),
            ];
        }
        return $schedule;
    }
}
