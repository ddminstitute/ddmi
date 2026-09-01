<?php
namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Customer;
use App\Models\FixedDeposit;
use App\Models\Transaction;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FixedDepositController extends Controller
{
    public function index(Request $request)
    {
        $query = FixedDeposit::with('account.user','customer');
        if ($request->status) $query->where('status', $request->status);
        $fds = $query->latest()->paginate(20)->withQueryString();
        $stats = [
            'active'       => FixedDeposit::where('status','active')->count(),
            'matured'      => FixedDeposit::where('status','matured')->count(),
            'total_amount' => FixedDeposit::where('status','active')->sum('principal_amount'),
        ];
        return view('fixed-deposits.index', compact('fds','stats'));
    }

    public function create()
    {
        $accounts  = Account::where('status','active')->with('user','customer')->get();
        $customers = Customer::where('status','active')->get();
        return view('fixed-deposits.create', compact('accounts','customers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'account_id'       => 'required|exists:accounts,id',
            'principal_amount' => 'required|numeric|min:500',
            'interest_rate'    => 'required|numeric|min:0|max:20',
            'tenure_months'    => 'required|integer|min:1|max:120',
            'compounding'      => 'required|in:monthly,quarterly,half_yearly,yearly,on_maturity',
            'start_date'       => 'required|date',
            'auto_renew'       => 'nullable|boolean',
        ]);

        $account = Account::findOrFail($data['account_id']);
        if ($account->balance < $data['principal_amount']) {
            return back()->with('error','Insufficient balance in account.')->withInput();
        }

        $maturity     = FixedDeposit::calculateMaturity($data['principal_amount'], $data['interest_rate'], $data['tenure_months'], $data['compounding']);
        $maturityDate = Carbon::parse($data['start_date'])->addMonths($data['tenure_months']);

        DB::transaction(function() use ($account, $data, $maturity, $maturityDate) {
            $before = $account->balance;
            $account->decrement('balance', $data['principal_amount']);
            Transaction::create([
                'account_id'       => $account->id,
                'transaction_type' => 'withdrawal',
                'transaction_mode' => 'internal',
                'amount'           => $data['principal_amount'],
                'balance_before'   => $before,
                'balance_after'    => $account->fresh()->balance,
                'description'      => 'FD opening — principal debit',
                'reference_number' => Transaction::generateReference(),
                'status'           => 'completed',
            ]);

            $fd = FixedDeposit::create([
                'fd_number'        => FixedDeposit::generateFdNumber(),
                'customer_id'      => $account->customer_id,
                'account_id'       => $account->id,
                'principal_amount' => $data['principal_amount'],
                'interest_rate'    => $data['interest_rate'],
                'compounding'      => $data['compounding'],
                'tenure_months'    => $data['tenure_months'],
                'start_date'       => $data['start_date'],
                'maturity_date'    => $maturityDate->toDateString(),
                'maturity_amount'  => $maturity,
                'interest_earned'  => 0,
                'status'           => 'active',
                'auto_renew'       => $request->boolean('auto_renew'),
                'created_by'       => auth()->id(),
            ]);

            ActivityLog::record('created', "FD {$fd->fd_number} opened for ₹" . number_format($data['principal_amount'],2), $fd);
        });

        return redirect()->route('fixed-deposits.index')->with('success','Fixed Deposit created successfully.');
    }

    public function show(FixedDeposit $fixedDeposit)
    {
        $fixedDeposit->load('account.user','customer');
        $daysToMaturity = now()->diffInDays($fixedDeposit->maturity_date, false);
        return view('fixed-deposits.show', compact('fixedDeposit','daysToMaturity'));
    }

    public function close(Request $request, FixedDeposit $fixedDeposit)
    {
        if ($fixedDeposit->status !== 'active') {
            return back()->with('error','FD is not active.');
        }

        $request->validate(['closure_reason' => 'required|string|max:255']);
        $isPremature = now()->lt($fixedDeposit->maturity_date);

        DB::transaction(function() use ($fixedDeposit, $request, $isPremature) {
            $payoutAmount = $fixedDeposit->maturity_amount;
            if ($isPremature) {
                $interest     = $fixedDeposit->maturity_amount - $fixedDeposit->principal_amount;
                $penalty      = $interest * ($fixedDeposit->premature_penalty_percent / 100);
                $payoutAmount = $fixedDeposit->maturity_amount - $penalty;
            }

            $account = $fixedDeposit->account;
            $before  = $account->balance;
            $account->increment('balance', $payoutAmount);
            Transaction::create([
                'account_id'       => $account->id,
                'transaction_type' => 'deposit',
                'transaction_mode' => 'internal',
                'amount'           => $payoutAmount,
                'balance_before'   => $before,
                'balance_after'    => $account->fresh()->balance,
                'description'      => 'FD ' . ($isPremature ? 'premature closure' : 'maturity payout') . ' - ' . $fixedDeposit->fd_number,
                'reference_number' => Transaction::generateReference(),
                'status'           => 'completed',
            ]);

            $fixedDeposit->update([
                'status'          => $isPremature ? 'premature_closed' : 'closed',
                'closure_reason'  => $request->closure_reason,
                'interest_earned' => $payoutAmount - $fixedDeposit->principal_amount,
                'closed_at'       => now(),
            ]);

            ActivityLog::record('updated', "FD {$fixedDeposit->fd_number} closed. Payout: ₹" . number_format($payoutAmount,2), $fixedDeposit);
        });

        return redirect()->route('fixed-deposits.index')->with('success','Fixed Deposit closed and payout credited to account.');
    }
}
