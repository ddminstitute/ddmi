<?php
namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\StandingInstruction;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class StandingInstructionController extends Controller
{
    public function index(Request $request)
    {
        $sis = StandingInstruction::with('account.user','toAccount.user')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()->paginate(20)->withQueryString();
        return view('standing-instructions.index', compact('sis'));
    }

    public function create()
    {
        $accounts = Account::where('status','active')->with('user','customer')->get();
        return view('standing-instructions.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'account_id'       => 'required|exists:accounts,id',
            'instruction_type' => 'required|in:transfer,emi_payment,utility,rd_installment',
            'to_account_id'    => 'nullable|exists:accounts,id',
            'amount'           => 'required|numeric|min:1',
            'frequency'        => 'required|in:weekly,monthly,quarterly,yearly',
            'execution_day'    => 'required|integer|min:1|max:28',
            'start_date'       => 'required|date',
            'end_date'         => 'nullable|date|after:start_date',
            'description'      => 'nullable|string|max:255',
        ]);
        $data['next_execution_date'] = $data['start_date'];
        $data['created_by'] = auth()->id();
        $si = StandingInstruction::create($data);
        ActivityLog::record('created', "Standing instruction created for account {$si->account->account_number}", $si);
        return redirect()->route('standing-instructions.index')->with('success','Standing instruction created.');
    }

    public function pause(StandingInstruction $standingInstruction)
    {
        $standingInstruction->update(['status' => $standingInstruction->status === 'active' ? 'paused' : 'active']);
        return back()->with('success','Standing instruction ' . ($standingInstruction->fresh()->status === 'active' ? 'resumed' : 'paused') . '.');
    }

    public function cancel(StandingInstruction $standingInstruction)
    {
        $standingInstruction->update(['status' => 'cancelled']);
        return back()->with('success','Standing instruction cancelled.');
    }
}
