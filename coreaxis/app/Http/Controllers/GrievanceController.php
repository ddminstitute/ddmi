<?php
namespace App\Http\Controllers;

use App\Models\Grievance;
use App\Models\Customer;
use App\Models\Account;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class GrievanceController extends Controller
{
    public function index(Request $request)
    {
        $grievances = Grievance::with('customer','reportedBy','assignedTo')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->priority, fn($q) => $q->where('priority', $request->priority))
            ->latest()->paginate(20)->withQueryString();
        $stats = [
            'open'        => Grievance::where('status','open')->count(),
            'in_progress' => Grievance::where('status','in_progress')->count(),
            'resolved'    => Grievance::where('status','resolved')->count(),
            'escalated'   => Grievance::where('status','escalated')->count(),
        ];
        return view('grievances.index', compact('grievances','stats'));
    }

    public function create()
    {
        $customers = Customer::where('status','active')->get();
        $accounts  = Account::where('status','active')->with('user','customer')->get();
        $staff     = User::whereIn('role',['admin','manager','staff'])->get();
        return view('grievances.create', compact('customers','accounts','staff'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'account_id'  => 'nullable|exists:accounts,id',
            'subject'     => 'required|string|max:200',
            'description' => 'required|string',
            'category'    => 'required|in:transaction,account,loan,service,staff,other',
            'priority'    => 'required|in:low,medium,high,urgent',
            'assigned_to' => 'nullable|exists:users,id',
        ]);
        $data['ticket_number'] = Grievance::generateTicket();
        $data['reported_by']   = auth()->id();
        $data['sla_due_date']  = match($data['priority']) {
            'urgent' => today()->addDay(),
            'high'   => today()->addDays(3),
            'medium' => today()->addDays(7),
            default  => today()->addDays(14),
        };

        $g = Grievance::create($data);
        ActivityLog::record('created', "Grievance {$g->ticket_number} raised: {$g->subject}", $g);
        return redirect()->route('grievances.index')->with('success',"Grievance {$g->ticket_number} raised.");
    }

    public function show(Grievance $grievance)
    {
        $grievance->load('customer','account','reportedBy','assignedTo');
        $staff = User::whereIn('role',['admin','manager','staff'])->get();
        return view('grievances.show', compact('grievance','staff'));
    }

    public function update(Request $request, Grievance $grievance)
    {
        $data = $request->validate([
            'status'           => 'required|in:open,in_progress,resolved,closed,escalated',
            'resolution_notes' => 'nullable|string',
            'assigned_to'      => 'nullable|exists:users,id',
        ]);
        if ($data['status'] === 'resolved') $data['resolved_at'] = now();
        $grievance->update($data);
        ActivityLog::record('updated', "Grievance {$grievance->ticket_number} status: {$data['status']}", $grievance);
        return back()->with('success','Grievance updated.');
    }
}
