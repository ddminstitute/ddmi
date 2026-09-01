<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\Loan;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function transactions(Request $request)
    {
        $base = Transaction::with('account.user');
        if ($request->from_date) $base->whereDate('created_at', '>=', $request->from_date);
        if ($request->to_date) $base->whereDate('created_at', '<=', $request->to_date);
        if ($request->type) $base->where('transaction_type', $request->type);

        $transactions = (clone $base)->latest()->paginate(50)->withQueryString();
        $totals = [
            'deposits' => (clone $base)->whereIn('transaction_type', ['deposit', 'transfer_in'])->sum('amount'),
            'withdrawals' => (clone $base)->whereIn('transaction_type', ['withdrawal', 'transfer_out'])->sum('amount'),
        ];
        return view('reports.transactions', compact('transactions', 'totals'));
    }

    public function statement(Request $request)
    {
        $accounts = Account::with('user')->get();
        $account = null;
        $transactions = collect();
        if ($request->account_id) {
            $account = Account::with('user')->findOrFail($request->account_id);
            $query = $account->transactions();
            if ($request->from_date) $query->whereDate('created_at', '>=', $request->from_date);
            if ($request->to_date) $query->whereDate('created_at', '<=', $request->to_date);
            $transactions = $query->paginate(50)->withQueryString();
        }
        return view('reports.statement', compact('accounts', 'account', 'transactions'));
    }

    public function loans(Request $request)
    {
        $query = Loan::with('user', 'account');
        if ($request->status) $query->where('status', $request->status);
        if ($request->type) $query->where('loan_type', $request->type);
        $loans = $query->latest()->paginate(50)->withQueryString();
        $summary = [
            'total_principal' => Loan::sum('principal_amount'),
            'total_disbursed' => Loan::whereIn('status', ['active', 'closed'])->sum('principal_amount'),
            'total_outstanding' => Loan::where('status', 'active')->sum('outstanding_amount'),
            'total_collected' => Loan::sum('paid_amount'),
        ];
        return view('reports.loans', compact('loans', 'summary'));
    }
}
