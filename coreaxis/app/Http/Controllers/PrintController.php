<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\CollectionEntry;
use App\Models\Loan;
use App\Models\Payslip;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    public function passbook(Account $account)
    {
        $transactions = $account->transactions()->orderBy('created_at')->get();
        return view('print.passbook', compact('account', 'transactions'));
    }

    public function statement(Request $request, Account $account)
    {
        $from = $request->get('from', now()->startOfMonth()->toDateString());
        $to   = $request->get('to', now()->toDateString());
        $transactions = $account->transactions()
            ->whereBetween('created_at', [$from, $to . ' 23:59:59'])
            ->orderBy('created_at')
            ->get();
        return view('print.statement', compact('account', 'transactions', 'from', 'to'));
    }

    public function receipt(Transaction $transaction)
    {
        $transaction->load('account');
        return view('print.receipt', compact('transaction'));
    }

    public function loanCertificate(Loan $loan)
    {
        $loan->load('customer', 'user', 'account', 'emiSchedules');
        return view('print.loan-certificate', compact('loan'));
    }

    public function collectionReceipt(CollectionEntry $collectionEntry)
    {
        $entry = $collectionEntry;
        $entry->load('collectionPlan.customer');
        return view('print.collection-receipt', compact('entry'));
    }

    public function payslip(Payslip $payslip)
    {
        $payslip->load('employee');
        return view('print.payslip', compact('payslip'));
    }
}
