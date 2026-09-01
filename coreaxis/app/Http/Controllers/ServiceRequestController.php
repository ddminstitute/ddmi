<?php
namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use App\Models\Customer;
use App\Models\Account;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ServiceRequestController extends Controller
{
    public function index(Request $request)
    {
        $requests = ServiceRequest::with('customer','account','requestedBy')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()->paginate(20)->withQueryString();
        return view('service-requests.index', compact('requests'));
    }

    public function create()
    {
        $customers = Customer::where('status','active')->get();
        $accounts  = Account::where('status','active')->with('user','customer')->get();
        return view('service-requests.create', compact('customers','accounts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id'  => 'nullable|exists:customers,id',
            'account_id'   => 'nullable|exists:accounts,id',
            'request_type' => 'required|in:stop_cheque,address_change,mobile_change,email_change,passbook_reissue,account_unfreeze,statement_request,nominee_change,other',
            'details'      => 'nullable|string|max:1000',
        ]);
        $data['request_number'] = ServiceRequest::generateNumber();
        $data['requested_by']   = auth()->id();
        $sr = ServiceRequest::create($data);
        ActivityLog::record('created', "Service request {$sr->request_number}: {$sr->request_type}", $sr);
        return redirect()->route('service-requests.index')->with('success',"Service request {$sr->request_number} raised.");
    }

    public function process(Request $request, ServiceRequest $serviceRequest)
    {
        $data = $request->validate([
            'status'  => 'required|in:approved,rejected,completed',
            'remarks' => 'nullable|string|max:500',
        ]);
        $serviceRequest->update([
            ...$data,
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);
        ActivityLog::record('updated', "Service request {$serviceRequest->request_number} {$data['status']}", $serviceRequest);
        return back()->with('success','Request '.$data['status'].' successfully.');
    }
}
