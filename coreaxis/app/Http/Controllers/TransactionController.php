<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('account.user');
        if ($request->type) $query->where('transaction_type', $request->type);
        if ($request->account_id) $query->where('account_id', $request->account_id);
        if ($request->from_date) $query->whereDate('created_at', '>=', $request->from_date);
        if ($request->to_date) $query->whereDate('created_at', '<=', $request->to_date);
        $transactions = $query->latest()->paginate(20)->withQueryString();
        $accounts = Account::where('status', 'active')->with('user')->get();
        return view('transactions.index', compact('transactions', 'accounts'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load('account.user', 'relatedAccount.user');
        return view('transactions.show', compact('transaction'));
    }

    public function depositForm()
    {
        $accounts = Account::where('status', 'active')->with('user')->get();
        return view('transactions.deposit', compact('accounts'));
    }

    public function deposit(Request $request)
    {
        $data = $request->validate([
            'account_id'  => 'required|exists:accounts,id',
            'amount'      => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        $account = Account::findOrFail($data['account_id']);
        if ($account->status !== 'active') {
            return back()->with('error', 'Account is not active.');
        }

        $txn = null;
        DB::transaction(function () use ($account, $data, &$txn) {
            $balanceBefore = $account->balance;
            $account->increment('balance', $data['amount']);
            $txn = Transaction::create([
                'account_id'       => $account->id,
                'transaction_type' => 'deposit',
                'amount'           => $data['amount'],
                'balance_before'   => $balanceBefore,
                'balance_after'    => $account->fresh()->balance,
                'description'      => $data['description'] ?? 'Cash deposit',
                'reference_number' => Transaction::generateReference(),
                'status'           => 'completed',
            ]);
        });
        if ($txn) app(NotificationService::class)->transactionAlert($txn->load('account.user'));

        return redirect()->route('transactions.index')
            ->with('success', "₹{$data['amount']} deposited successfully.");
    }

    public function withdrawForm()
    {
        $accounts = Account::where('status', 'active')->with('user')->get();
        return view('transactions.withdraw', compact('accounts'));
    }

    public function withdraw(Request $request)
    {
        $data = $request->validate([
            'account_id'  => 'required|exists:accounts,id',
            'amount'      => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        $account = Account::findOrFail($data['account_id']);
        if ($account->status !== 'active') {
            return back()->with('error', 'Account is not active.');
        }
        if ($account->balance < $data['amount']) {
            return back()->with('error', 'Insufficient balance.');
        }

        $txn = null;
        DB::transaction(function () use ($account, $data, &$txn) {
            $balanceBefore = $account->balance;
            $account->decrement('balance', $data['amount']);
            $txn = Transaction::create([
                'account_id'       => $account->id,
                'transaction_type' => 'withdrawal',
                'amount'           => $data['amount'],
                'balance_before'   => $balanceBefore,
                'balance_after'    => $account->fresh()->balance,
                'description'      => $data['description'] ?? 'Cash withdrawal',
                'reference_number' => Transaction::generateReference(),
                'status'           => 'completed',
            ]);
        });
        if ($txn) app(NotificationService::class)->transactionAlert($txn->load('account.user'));

        return redirect()->route('transactions.index')
            ->with('success', "₹{$data['amount']} withdrawn successfully.");
    }

    public function transferForm()
    {
        $accounts = Account::where('status', 'active')->with('user')->get();
        return view('transactions.transfer', compact('accounts'));
    }

    public function transfer(Request $request)
    {
        $data = $request->validate([
            'from_account_id' => 'required|exists:accounts,id',
            'to_account_id'   => 'required|exists:accounts,id|different:from_account_id',
            'amount'          => 'required|numeric|min:1',
            'description'     => 'nullable|string|max:255',
        ]);

        $from = Account::findOrFail($data['from_account_id']);
        $to   = Account::findOrFail($data['to_account_id']);

        if ($from->status !== 'active' || $to->status !== 'active') {
            return back()->with('error', 'One or both accounts are not active.');
        }
        if ($from->balance < $data['amount']) {
            return back()->with('error', 'Insufficient balance in source account.');
        }

        DB::transaction(function () use ($from, $to, $data) {
            $desc       = $data['description'] ?? 'Fund transfer';
            $fromBefore = $from->balance;
            $from->decrement('balance', $data['amount']);
            Transaction::create([
                'account_id'        => $from->id,
                'transaction_type'  => 'transfer_out',
                'amount'            => $data['amount'],
                'balance_before'    => $fromBefore,
                'balance_after'     => $from->fresh()->balance,
                'description'       => $desc . " to {$to->account_number}",
                'reference_number'  => Transaction::generateReference(),
                'related_account_id'=> $to->id,
                'status'            => 'completed',
            ]);

            $toBefore = $to->balance;
            $to->increment('balance', $data['amount']);
            Transaction::create([
                'account_id'        => $to->id,
                'transaction_type'  => 'transfer_in',
                'amount'            => $data['amount'],
                'balance_before'    => $toBefore,
                'balance_after'     => $to->fresh()->balance,
                'description'       => $desc . " from {$from->account_number}",
                'reference_number'  => Transaction::generateReference(),
                'related_account_id'=> $from->id,
                'status'            => 'completed',
            ]);
        });

        return redirect()->route('transactions.index')
            ->with('success', "Transfer of ₹{$data['amount']} completed successfully.");
    }
}
