<?php
namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionReversalController extends Controller
{
    public function reverse(Request $request, Transaction $transaction)
    {
        if ($transaction->is_reversed) {
            return back()->with('error','Transaction is already reversed.');
        }
        if (in_array($transaction->transaction_type, ['transfer_in','transfer_out'])) {
            return back()->with('error','Transfer transactions require manual reversal of both legs. Contact admin.');
        }

        $request->validate(['reversal_reason' => 'required|string|max:255']);

        DB::transaction(function() use ($transaction, $request) {
            $account = $transaction->account;

            if ($transaction->transaction_type === 'deposit') {
                if ($account->balance < $transaction->amount) {
                    throw new \Exception('Insufficient balance to reverse this deposit.');
                }
                $before = $account->balance;
                $account->decrement('balance', $transaction->amount);
                $revType = 'withdrawal';
            } else {
                $before = $account->balance;
                $account->increment('balance', $transaction->amount);
                $revType = 'deposit';
            }

            $reversal = Transaction::create([
                'account_id'              => $account->id,
                'transaction_type'        => $revType,
                'transaction_mode'        => 'internal',
                'amount'                  => $transaction->amount,
                'balance_before'          => $before,
                'balance_after'           => $account->fresh()->balance,
                'description'             => "REVERSAL of {$transaction->reference_number}: {$request->reversal_reason}",
                'reference_number'        => Transaction::generateReference(),
                'status'                  => 'completed',
                'parent_transaction_id'   => $transaction->id,
            ]);

            $transaction->update([
                'is_reversed'      => true,
                'reversed_by'      => auth()->id(),
                'reversed_at'      => now(),
                'reversal_reason'  => $request->reversal_reason,
            ]);

            ActivityLog::record('updated', "Transaction {$transaction->reference_number} reversed. Reason: {$request->reversal_reason}. Reversal ref: {$reversal->reference_number}", $transaction);
        });

        return back()->with('success','Transaction reversed successfully.');
    }
}
