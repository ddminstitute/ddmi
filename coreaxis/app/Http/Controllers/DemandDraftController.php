<?php
namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\DemandDraft;
use App\Models\Transaction;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DemandDraftController extends Controller
{
    public function index(Request $request)
    {
        $dds = DemandDraft::with('account.user')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()->paginate(20)->withQueryString();
        return view('demand-drafts.index', compact('dds'));
    }

    public function create()
    {
        $accounts = Account::where('status','active')->with('user','customer')->get();
        return view('demand-drafts.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'account_id'      => 'required|exists:accounts,id',
            'instrument_type' => 'required|in:demand_draft,pay_order',
            'payee_name'      => 'required|string|max:100',
            'payable_at_city' => 'nullable|string|max:50',
            'payable_at_bank' => 'nullable|string|max:100',
            'amount'          => 'required|numeric|min:100',
            'issue_date'      => 'required|date',
        ]);

        $charges = $data['amount'] <= 10000 ? 50 : ($data['amount'] <= 100000 ? 100 : 200);
        $totalDebit = $data['amount'] + $charges;
        $account = Account::findOrFail($data['account_id']);

        if ($account->balance < $totalDebit) {
            return back()->with('error','Insufficient balance (amount + charges ₹'.number_format($charges,2).').')->withInput();
        }

        DB::transaction(function() use ($account, $data, $charges, $totalDebit) {
            $before = $account->balance;
            $account->decrement('balance', $totalDebit);
            $ddNum = DemandDraft::generateDdNumber();

            Transaction::create([
                'account_id'       => $account->id,
                'transaction_type' => 'withdrawal',
                'transaction_mode' => 'internal',
                'amount'           => $totalDebit,
                'balance_before'   => $before,
                'balance_after'    => $account->fresh()->balance,
                'description'      => strtoupper(str_replace('_',' ',$data['instrument_type'])). " - {$ddNum} to {$data['payee_name']}",
                'reference_number' => Transaction::generateReference(),
                'status'           => 'completed',
            ]);

            $dd = DemandDraft::create([
                ...$data,
                'dd_number'    => $ddNum,
                'charges'      => $charges,
                'total_debited'=> $totalDebit,
                'valid_until'  => now()->addMonths(3)->toDateString(),
                'created_by'   => auth()->id(),
            ]);
            ActivityLog::record('created', "{$dd->instrument_type} {$dd->dd_number} issued for ₹".number_format($dd->amount,2), $dd);
        });

        return redirect()->route('demand-drafts.index')->with('success','Demand Draft issued and account debited.');
    }

    public function cancel(Request $request, DemandDraft $demandDraft)
    {
        $request->validate(['cancellation_reason' => 'required|string|max:255']);
        if ($demandDraft->status !== 'active') return back()->with('error','Cannot cancel this DD.');

        DB::transaction(function() use ($demandDraft, $request) {
            // Refund: credit back principal (charges not refunded)
            $account = $demandDraft->account;
            $before  = $account->balance;
            $account->increment('balance', $demandDraft->amount);
            Transaction::create([
                'account_id'       => $account->id,
                'transaction_type' => 'deposit',
                'transaction_mode' => 'internal',
                'amount'           => $demandDraft->amount,
                'balance_before'   => $before,
                'balance_after'    => $account->fresh()->balance,
                'description'      => "DD Cancellation refund - {$demandDraft->dd_number}",
                'reference_number' => Transaction::generateReference(),
                'status'           => 'completed',
            ]);
            $demandDraft->update([
                'status'               => 'cancelled',
                'cancellation_reason'  => $request->cancellation_reason,
                'cancelled_at'         => now(),
            ]);
        });

        return redirect()->route('demand-drafts.index')->with('success','DD cancelled and principal refunded.');
    }

    public function printReceipt(DemandDraft $demandDraft)
    {
        $demandDraft->load('account.user');
        return view('print.dd-receipt', compact('demandDraft'));
    }
}
