<?php
namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\FundTransfer;
use App\Models\Transaction;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FundTransferController extends Controller
{
    public function index(Request $request)
    {
        $transfers = FundTransfer::with('account.user')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->mode, fn($q) => $q->where('transfer_mode', $request->mode))
            ->latest()->paginate(20)->withQueryString();
        return view('fund-transfers.index', compact('transfers'));
    }

    public function create()
    {
        $accounts = Account::where('status','active')->with('user','customer')->get();
        return view('fund-transfers.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'account_id'          => 'required|exists:accounts,id',
            'transfer_mode'       => 'required|in:neft,rtgs,imps,upi',
            'amount'              => 'required|numeric|min:1',
            'beneficiary_name'    => 'required|string|max:100',
            'beneficiary_account' => 'required|string|max:25',
            'beneficiary_ifsc'    => 'required|string|max:12',
            'beneficiary_bank'    => 'nullable|string|max:100',
            'description'         => 'nullable|string|max:255',
        ]);

        if ($data['transfer_mode'] === 'rtgs' && $data['amount'] < 200000) {
            return back()->with('error','RTGS minimum amount is ₹2,00,000.')->withInput();
        }

        $account = Account::findOrFail($data['account_id']);
        $charges = match($data['transfer_mode']) {
            'neft'  => ($data['amount'] <= 10000 ? 2.50 : ($data['amount'] <= 100000 ? 5 : ($data['amount'] <= 200000 ? 15 : 25))),
            'rtgs'  => ($data['amount'] <= 500000 ? 25 : 50),
            'imps'  => ($data['amount'] <= 10000 ? 2.50 : ($data['amount'] <= 100000 ? 5 : 15)),
            'upi'   => 0,
            default => 0,
        };
        $totalDebit = $data['amount'] + $charges;

        if ($account->balance < $totalDebit) {
            return back()->with('error','Insufficient balance (including charges ₹'.number_format($charges,2).').')->withInput();
        }

        DB::transaction(function() use ($account, $data, $charges, $totalDebit) {
            $before = $account->balance;
            $account->decrement('balance', $totalDebit);
            $ref = FundTransfer::generateReference();

            Transaction::create([
                'account_id'       => $account->id,
                'transaction_type' => 'withdrawal',
                'transaction_mode' => $data['transfer_mode'],
                'amount'           => $totalDebit,
                'balance_before'   => $before,
                'balance_after'    => $account->fresh()->balance,
                'description'      => strtoupper($data['transfer_mode'])." to {$data['beneficiary_name']} / {$data['beneficiary_account']}",
                'reference_number' => $ref,
                'status'           => 'completed',
            ]);

            $ft = FundTransfer::create([
                ...$data,
                'reference_number' => $ref,
                'charges'          => $charges,
                'status'           => 'processing',
                'initiated_at'     => now(),
                'created_by'       => auth()->id(),
            ]);
            ActivityLog::record('created', "Fund transfer {$ref} via {$data['transfer_mode']} for ₹".number_format($data['amount'],2), $ft);
        });

        return redirect()->route('fund-transfers.index')->with('success','Fund transfer initiated successfully.');
    }

    public function updateStatus(Request $request, FundTransfer $fundTransfer)
    {
        $request->validate(['status' => 'required|in:completed,failed,reversed', 'bank_reference' => 'nullable|string|max:50']);
        $fundTransfer->update([
            'status'         => $request->status,
            'bank_reference' => $request->bank_reference,
            'completed_at'   => in_array($request->status,['completed','failed']) ? now() : null,
            'failure_reason' => $request->failure_reason,
        ]);
        return back()->with('success','Transfer status updated.');
    }
}
