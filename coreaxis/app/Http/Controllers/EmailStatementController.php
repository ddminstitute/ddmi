<?php

namespace App\Http\Controllers;

use App\Mail\AccountStatement;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailStatementController extends Controller
{
    public function send(Request $request, Account $account)
    {
        $data = $request->validate([
            'from_date' => 'required|date',
            'to_date'   => 'required|date|after_or_equal:from_date',
            'email'     => 'required|email',
        ]);

        $transactions = Transaction::where('account_id', $account->id)
            ->whereDate('created_at', '>=', $data['from_date'])
            ->whereDate('created_at', '<=', $data['to_date'])
            ->latest()
            ->get();

        Mail::to($data['email'])->send(new AccountStatement(
            $account,
            $transactions,
            $data['from_date'],
            $data['to_date']
        ));

        return back()->with('success', "Statement emailed to {$data['email']} successfully.");
    }
}
