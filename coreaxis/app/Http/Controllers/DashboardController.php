<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\CollectionEntry;
use App\Models\Employee;
use App\Models\Loan;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = $user->role ?? 'cashier';

        $data = [];

        if ($user->hasFeature('accounts')) {
            $data['totalAccounts']   = Account::where('status', 'active')->count();
            $data['totalBalance']    = Account::where('status', 'active')->sum('balance');
            $data['recentAccounts']  = Account::with('user')->latest()->limit(5)->get();
        }

        if ($user->hasFeature('transactions')) {
            $data['monthlyDeposits']     = Transaction::where('transaction_type', 'deposit')
                ->whereMonth('created_at', now()->month)->sum('amount');
            $data['recentTransactions']  = Transaction::with('account.user')->latest()->limit(10)->get();
        }

        if ($user->hasFeature('loans')) {
            $data['activeLoans']      = Loan::where('status', 'active')->count();
            $data['totalLoansAmount'] = Loan::where('status', 'active')->sum('outstanding_amount');
            $data['pendingLoans']     = Loan::where('status', 'pending')->count();
        }

        if ($user->hasFeature('collections')) {
            $data['todayCollections'] = CollectionEntry::whereDate('created_at', today())->sum('amount');
        }

        if ($user->hasFeature('employees')) {
            $data['totalEmployees'] = Employee::where('status', 'active')->count();
        }

        return view('dashboard.index', $data);
    }
}
