<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class KycController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::when($request->kyc_status, fn($q) => $q->where('kyc_status', $request->kyc_status))
            ->latest()->paginate(20)->withQueryString();
        $stats = [
            'pending'  => Customer::where('kyc_status','pending')->count(),
            'verified' => Customer::where('kyc_status','verified')->count(),
            'rejected' => Customer::where('kyc_status','rejected')->count(),
            'expired'  => Customer::where('kyc_status','expired')->count(),
        ];
        return view('kyc.index', compact('customers','stats'));
    }

    public function verify(Request $request, Customer $customer)
    {
        $request->validate([
            'action'      => 'required|in:verified,rejected',
            'kyc_remarks' => 'nullable|string|max:500',
        ]);

        $customer->update([
            'kyc_status'      => $request->action,
            'kyc_verified_at' => $request->action === 'verified' ? now() : null,
            'kyc_verified_by' => auth()->id(),
            'kyc_remarks'     => $request->kyc_remarks,
            'kyc_expiry_date' => $request->action === 'verified' ? now()->addYears(2)->toDateString() : null,
        ]);

        ActivityLog::record('updated', "KYC {$request->action} for customer {$customer->name}", $customer);

        return back()->with('success', "KYC {$request->action} for {$customer->name}.");
    }
}
