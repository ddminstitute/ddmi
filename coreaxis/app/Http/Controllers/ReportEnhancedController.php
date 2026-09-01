<?php
namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\EmiSchedule;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\FixedDeposit;
use App\Models\RecurringDeposit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportEnhancedController extends Controller
{
    public function npa(Request $request)
    {
        $query = Loan::with('customer','account','emiSchedules')
            ->whereIn('status',['active'])
            ->where(function($q) {
                $q->where('is_npa', true)
                  ->orWhereHas('emiSchedules', fn($s) => $s->where('status','overdue'));
            });

        if ($request->bucket) {
            $buckets = [
                '30'  => [1,30],
                '60'  => [31,60],
                '90'  => [61,90],
                '180' => [91,180],
                '180+'=> [181,9999],
            ];
            if (isset($buckets[$request->bucket])) {
                [$min,$max] = $buckets[$request->bucket];
                $query->where('overdue_days','>=',$min)->where('overdue_days','<=',$max);
            }
        }

        $loans = $query->latest()->paginate(25)->withQueryString();
        $summary = [
            'total_npa_accounts'  => Loan::where('is_npa',true)->count(),
            'total_npa_amount'    => Loan::where('is_npa',true)->sum('outstanding_amount'),
            'overdue_30'          => Loan::where('overdue_days','>=',1)->where('overdue_days','<=',30)->count(),
            'overdue_60'          => Loan::where('overdue_days','>=',31)->where('overdue_days','<=',60)->count(),
            'overdue_90'          => Loan::where('overdue_days','>=',61)->where('overdue_days','<=',90)->count(),
            'overdue_90_plus'     => Loan::where('overdue_days','>=',91)->count(),
            'total_penalties'     => Loan::sum('penalty_amount'),
        ];
        return view('reports.npa', compact('loans','summary'));
    }

    public function cashflow(Request $request)
    {
        $from = $request->get('from', now()->startOfMonth()->toDateString());
        $to   = $request->get('to', now()->toDateString());

        $dailyFlow = Transaction::selectRaw('DATE(created_at) as date, transaction_type, SUM(amount) as total')
            ->whereDate('created_at','>=',$from)->whereDate('created_at','<=',$to)
            ->groupByRaw('DATE(created_at), transaction_type')
            ->orderBy('date')->get();

        $totalDeposits    = Transaction::whereDate('created_at','>=',$from)->whereDate('created_at','<=',$to)->where('transaction_type','deposit')->sum('amount');
        $totalWithdrawals = Transaction::whereDate('created_at','>=',$from)->whereDate('created_at','<=',$to)->where('transaction_type','withdrawal')->sum('amount');
        $totalBalance     = Account::where('status','active')->sum('balance');
        $totalLoans       = Loan::where('status','active')->sum('outstanding_amount');
        $totalFD          = FixedDeposit::where('status','active')->sum('principal_amount');
        $totalRD          = RecurringDeposit::where('status','active')->sum('total_deposited');

        return view('reports.cashflow', compact('dailyFlow','totalDeposits','totalWithdrawals','totalBalance','totalLoans','totalFD','totalRD','from','to'));
    }

    public function regulatory(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        [$year, $mon] = explode('-', $month);

        $data = [
            'month'             => $month,
            'total_deposits'    => Account::sum('balance'),
            'total_loan_portfolio' => Loan::where('status','active')->sum('outstanding_amount'),
            'npa_amount'        => Loan::where('is_npa',true)->sum('outstanding_amount'),
            'new_loans'         => Loan::whereYear('created_at',$year)->whereMonth('created_at',$mon)->count(),
            'new_loans_amount'  => Loan::whereYear('created_at',$year)->whereMonth('created_at',$mon)->sum('amount'),
            'accounts_opened'   => Account::whereYear('created_at',$year)->whereMonth('created_at',$mon)->count(),
            'fd_portfolio'      => FixedDeposit::where('status','active')->sum('principal_amount'),
            'rd_portfolio'      => RecurringDeposit::where('status','active')->sum('total_deposited'),
        ];

        if ($request->format === 'csv') {
            return $this->exportCsv($data, "regulatory-return-{$month}.csv");
        }

        return view('reports.regulatory', compact('data','month'));
    }

    private function exportCsv(array $data, string $filename)
    {
        $headers = ['Content-Type' => 'text/csv','Content-Disposition' => "attachment; filename={$filename}"];
        $callback = function() use ($data) {
            $f = fopen('php://output','w');
            fputcsv($f, ['Parameter','Value']);
            foreach ($data as $k => $v) {
                fputcsv($f, [ucwords(str_replace('_',' ',$k)), $v]);
            }
            fclose($f);
        };
        return response()->stream($callback, 200, $headers);
    }
}
