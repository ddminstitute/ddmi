<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\Loan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAccounts = Account::where('status', 'active')->count();
        $totalBalance = Account::where('status', 'active')->sum('balance');
        $activeLoans = Loan::where('status', 'active')->count();
        $totalLoansAmount = Loan::where('status', 'active')->sum('outstanding_amount');
        $monthlyDeposits = Transaction::where('transaction_type', 'deposit')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');
        $pendingLoans = Loan::where('status', 'pending')->count();
        $recentTransactions = Transaction::with('account.user')
            ->latest()->limit(10)->get();
        $recentAccounts = Account::with('user')->latest()->limit(5)->get();

        return view('dashboard.index', compact(
            'totalAccounts', 'totalBalance', 'activeLoans', 'totalLoansAmount',
            'monthlyDeposits', 'pendingLoans', 'recentTransactions', 'recentAccounts'
        ));
    }
}
