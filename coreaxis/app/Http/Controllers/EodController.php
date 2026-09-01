<?php
namespace App\Http\Controllers;

use App\Models\EodRecord;
use App\Models\Transaction;
use App\Models\EmiSchedule;
use App\Models\Account;
use App\Models\Loan;
use App\Models\FixedDeposit;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EodController extends Controller
{
    public function index()
    {
        $records = EodRecord::latest('business_date')->paginate(30);
        $today   = today()->toDateString();
        $alreadyDone = EodRecord::where('business_date', $today)->exists();
        return view('eod.index', compact('records','today','alreadyDone'));
    }

    public function process(Request $request)
    {
        $date = $request->get('business_date', today()->toDateString());
        if (EodRecord::where('business_date', $date)->exists()) {
            return back()->with('error','EOD already processed for this date.');
        }

        DB::transaction(function() use ($date) {
            $d = Carbon::parse($date);

            $overdueCount = 0;
            EmiSchedule::where('status','pending')
                ->whereDate('due_date', '<', $d)
                ->chunk(100, function($emis) use (&$overdueCount, $d) {
                    foreach ($emis as $emi) {
                        $days = Carbon::parse($emi->due_date)->diffInDays($d);
                        $loan = $emi->loan;
                        if (!$loan) continue;
                        $penalty = round($emi->emi_amount * ($loan->penal_rate / 100) * $days / 30, 2);
                        $emi->update(['status' => 'overdue','overdue_days' => $days,'penalty_amount' => $penalty]);
                        $loan->increment('penalty_amount', $penalty);
                        if ($days >= 90 && !$loan->is_npa) {
                            $loan->update(['is_npa' => true,'npa_date' => today()->toDateString()]);
                        }
                        $overdueCount++;
                    }
                });

            $interestPosted = 0;
            if ($d->day === 1) {
                FixedDeposit::where('status','active')
                    ->whereIn('compounding',['monthly','quarterly','half_yearly','yearly'])
                    ->chunk(50, function($fds) use (&$interestPosted, $d) {
                        foreach ($fds as $fd) {
                            $monthlyRate = $fd->interest_rate / 100 / 12;
                            $monthlyInterest = round($fd->principal_amount * $monthlyRate, 2);
                            $fd->increment('interest_earned', $monthlyInterest);
                            $interestPosted += $monthlyInterest;
                        }
                    });
            }

            $txns = Transaction::whereDate('created_at', $date);
            $totalDeposits    = $txns->clone()->where('transaction_type','deposit')->sum('amount');
            $totalWithdrawals = $txns->clone()->where('transaction_type','withdrawal')->sum('amount');
            $totalTransfers   = $txns->clone()->whereIn('transaction_type',['transfer_in','transfer_out'])->sum('amount');

            EodRecord::create([
                'business_date'       => $date,
                'total_deposits'      => $totalDeposits,
                'total_withdrawals'   => $totalWithdrawals,
                'total_transfers'     => $totalTransfers,
                'interest_posted'     => $interestPosted,
                'penalties_applied'   => EmiSchedule::whereDate('updated_at',$date)->where('status','overdue')->sum('penalty_amount'),
                'accounts_count'      => Account::where('status','active')->count(),
                'transactions_count'  => $txns->count(),
                'emis_marked_overdue' => $overdueCount,
                'status'              => 'completed',
                'processed_by'        => auth()->id(),
                'processed_at'        => now(),
            ]);

            ActivityLog::record('created', "EOD processed for {$date}. Overdue EMIs: {$overdueCount}, Interest posted: ₹".number_format($interestPosted,2));
        });

        return redirect()->route('eod.index')->with('success',"EOD for {$date} processed successfully.");
    }
}
