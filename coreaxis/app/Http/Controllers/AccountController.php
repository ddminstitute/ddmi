<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $query = Account::with('user');
        if ($request->search) {
            $query->where('account_number', 'like', "%{$request->search}%")
                ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
        }
        if ($request->type) $query->where('account_type', $request->type);
        if ($request->status) $query->where('status', $request->status);
        $accounts = $query->latest()->paginate(15)->withQueryString();
        return view('accounts.index', compact('accounts'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('accounts.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'account_type' => 'required|in:savings,checking,current,fixed_deposit',
            'currency' => 'required|size:3',
            'initial_deposit' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $account = Account::create([
            'user_id' => $data['user_id'],
            'account_number' => Account::generateAccountNumber(),
            'account_type' => $data['account_type'],
            'balance' => $data['initial_deposit'],
            'currency' => strtoupper($data['currency']),
            'status' => 'active',
            'notes' => $data['notes'] ?? null,
        ]);

        if ($data['initial_deposit'] > 0) {
            \App\Models\Transaction::create([
                'account_id' => $account->id,
                'transaction_type' => 'deposit',
                'amount' => $data['initial_deposit'],
                'balance_before' => 0,
                'balance_after' => $data['initial_deposit'],
                'description' => 'Initial deposit on account opening',
                'reference_number' => \App\Models\Transaction::generateReference(),
                'status' => 'completed',
            ]);
        }

        return redirect()->route('accounts.show', $account)
            ->with('success', "Account {$account->account_number} created successfully.");
    }

    public function show(Account $account)
    {
        $account->load('user');
        $transactions = $account->transactions()->paginate(20)->withQueryString();
        return view('accounts.show', compact('account', 'transactions'));
    }

    public function edit(Account $account)
    {
        return view('accounts.edit', compact('account'));
    }

    public function update(Request $request, Account $account)
    {
        $data = $request->validate([
            'status' => 'required|in:active,inactive,frozen',
            'notes' => 'nullable|string|max:500',
        ]);
        $account->update($data);
        return redirect()->route('accounts.show', $account)
            ->with('success', 'Account updated successfully.');
    }

    public function destroy(Account $account)
    {
        if ($account->balance > 0) {
            return back()->with('error', 'Cannot close account with remaining balance.');
        }
        $account->update(['status' => 'inactive']);
        return redirect()->route('accounts.index')
            ->with('success', 'Account closed successfully.');
    }
}
