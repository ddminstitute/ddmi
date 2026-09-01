<?php
namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use Illuminate\Http\Request;

class GeneralLedgerController extends Controller
{
    public function chartOfAccounts()
    {
        $accounts = ChartOfAccount::orderBy('code')->get()->groupBy('type');
        return view('gl.chart-of-accounts', compact('accounts'));
    }

    public function storeAccount(Request $request)
    {
        $data = $request->validate([
            'code'           => 'required|string|max:10|unique:chart_of_accounts,code',
            'name'           => 'required|string|max:100',
            'type'           => 'required|in:asset,liability,equity,income,expense',
            'normal_balance' => 'required|in:debit,credit',
            'description'    => 'nullable|string|max:255',
        ]);
        ChartOfAccount::create($data);
        return back()->with('success', "Account {$data['code']} added.");
    }

    public function journalEntries(Request $request)
    {
        $query = JournalEntry::with('lines.account');
        if ($request->from) $query->whereDate('entry_date','>=',$request->from);
        if ($request->to)   $query->whereDate('entry_date','<=',$request->to);
        if ($request->source_type) $query->where('source_type',$request->source_type);
        $entries = $query->latest()->paginate(25)->withQueryString();
        return view('gl.journal-entries', compact('entries'));
    }

    public function showEntry(JournalEntry $journalEntry)
    {
        $journalEntry->load('lines.account');
        return view('gl.journal-entry-show', compact('journalEntry'));
    }

    public function trialBalance(Request $request)
    {
        $asOfDate = $request->as_of ?? today()->toDateString();
        $accounts = ChartOfAccount::where('is_active',true)->orderBy('code')->get()
            ->map(function ($acc) use ($asOfDate) {
                $acc->total_debits  = JournalEntryLine::where('chart_of_account_id',$acc->id)->where('type','debit')->whereHas('journalEntry',fn($q)=>$q->whereDate('entry_date','<=',$asOfDate))->sum('amount');
                $acc->total_credits = JournalEntryLine::where('chart_of_account_id',$acc->id)->where('type','credit')->whereHas('journalEntry',fn($q)=>$q->whereDate('entry_date','<=',$asOfDate))->sum('amount');
                $acc->net_balance   = $acc->normal_balance==='debit' ? ($acc->total_debits-$acc->total_credits) : ($acc->total_credits-$acc->total_debits);
                return $acc;
            })->filter(fn($a)=>$a->total_debits>0||$a->total_credits>0);
        $totals = ['debit'=>$accounts->sum('total_debits'),'credit'=>$accounts->sum('total_credits')];
        return view('gl.trial-balance', compact('accounts','totals','asOfDate'));
    }

    public function ledger(Request $request)
    {
        $accounts = ChartOfAccount::orderBy('code')->get();
        $selected = null; $lines = collect();
        if ($request->account_id) {
            $selected = ChartOfAccount::findOrFail($request->account_id);
            $query = JournalEntryLine::with('journalEntry')->where('chart_of_account_id',$selected->id);
            if ($request->from) $query->whereHas('journalEntry',fn($q)=>$q->whereDate('entry_date','>=',$request->from));
            if ($request->to)   $query->whereHas('journalEntry',fn($q)=>$q->whereDate('entry_date','<=',$request->to));
            $lines = $query->join('journal_entries','journal_entries.id','=','journal_entry_lines.journal_entry_id')
                ->orderBy('journal_entries.entry_date')->select('journal_entry_lines.*')->with('journalEntry')->get();
        }
        return view('gl.ledger', compact('accounts','selected','lines'));
    }
}
