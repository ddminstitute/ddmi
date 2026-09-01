<?php
namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Cheque;
use App\Models\Transaction;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChequeController extends Controller
{
    public function index(Request $request)
    {
        $cheques = Cheque::with('account.user')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->account_id, fn($q) => $q->where('account_id', $request->account_id))
            ->latest()->paginate(20)->withQueryString();
        $accounts = Account::where('status','active')->with('user')->get();
        return view('cheques.index', compact('cheques','accounts'));
    }

    public function create()
    {
        $accounts = Account::where('status','active')->with('user','customer')->get();
        return view('cheques.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'account_id'    => 'required|exists:accounts,id',
            'cheque_number' => 'required|string|max:20',
            'cheque_type'   => 'required|in:issued,received',
            'drawee_bank'   => 'nullable|string|max:100',
            'drawee_branch' => 'nullable|string|max:100',
            'drawer_name'   => 'nullable|string|max:100',
            'amount'        => 'required|numeric|min:1',
            'cheque_date'   => 'required|date',
            'deposit_date'  => 'nullable|date',
            'description'   => 'nullable|string|max:255',
        ]);
        $data['created_by']        = auth()->id();
        $data['reference_number']  = Transaction::generateReference();
        $cheque = Cheque::create($data);
        ActivityLog::record('created', "Cheque {$cheque->cheque_number} recorded for ₹".number_format($cheque->amount,2), $cheque);
        return redirect()->route('cheques.index')->with('success','Cheque recorded successfully.');
    }

    public function updateStatus(Request $request, Cheque $cheque)
    {
        $request->validate([
            'status'        => 'required|in:cleared,bounced,cancelled',
            'clearing_date' => 'nullable|date',
            'bounce_reason' => 'nullable|string|max:255',
        ]);

        DB::transaction(function() use ($cheque, $request) {
            $cheque->update([
                'status'        => $request->status,
                'clearing_date' => $request->clearing_date,
                'bounce_reason' => $request->bounce_reason,
            ]);

            if ($request->status === 'cleared' && $cheque->cheque_type === 'received') {
                $account = $cheque->account;
                $before  = $account->balance;
                $account->increment('balance', $cheque->amount);
                Transaction::create([
                    'account_id'       => $account->id,
                    'transaction_type' => 'deposit',
                    'transaction_mode' => 'cheque',
                    'amount'           => $cheque->amount,
                    'balance_before'   => $before,
                    'balance_after'    => $account->fresh()->balance,
                    'description'      => "Cheque cleared - {$cheque->cheque_number} from {$cheque->drawer_name}",
                    'reference_number' => $cheque->reference_number,
                    'status'           => 'completed',
                ]);
            } elseif ($request->status === 'bounced' && $cheque->cheque_type === 'received') {
                if ($cheque->bounce_charge > 0) {
                    $account = $cheque->account;
                    $before  = $account->balance;
                    $account->decrement('balance', $cheque->bounce_charge);
                    Transaction::create([
                        'account_id'       => $account->id,
                        'transaction_type' => 'withdrawal',
                        'transaction_mode' => 'internal',
                        'amount'           => $cheque->bounce_charge,
                        'balance_before'   => $before,
                        'balance_after'    => $account->fresh()->balance,
                        'description'      => "Cheque bounce charge - {$cheque->cheque_number}",
                        'reference_number' => Transaction::generateReference(),
                        'status'           => 'completed',
                    ]);
                }
            }

            ActivityLog::record('updated', "Cheque {$cheque->cheque_number} marked as {$request->status}", $cheque);
        });

        return back()->with('success','Cheque status updated.');
    }
}
