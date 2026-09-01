<?php
namespace App\Http\Controllers;
use App\Models\CollectionPlan;
use App\Models\CollectionEntry;
use App\Models\Customer;
use App\Models\Account;
use Illuminate\Http\Request;

class CollectionController extends Controller {
    public function index(Request $request) {
        $query = CollectionPlan::with('customer');
        if ($request->type) $query->where('collection_type', $request->type);
        if ($request->status) $query->where('status', $request->status);
        if ($request->search) {
            $query->where('plan_number','like',"%{$request->search}%")
                  ->orWhereHas('customer', fn($q) => $q->where('name','like',"%{$request->search}%"));
        }
        $plans = $query->latest()->paginate(15)->withQueryString();
        return view('collections.index', compact('plans'));
    }
    public function create() {
        $customers = Customer::where('status','active')->orderBy('name')->get();
        return view('collections.create', compact('customers'));
    }
    public function store(Request $request) {
        $data = $request->validate([
            'customer_id'=>'required|exists:customers,id',
            'account_id'=>'nullable|exists:accounts,id',
            'plan_name'=>'required|string|max:100',
            'collection_type'=>'required|in:daily,weekly,monthly',
            'collection_amount'=>'required|numeric|min:1',
            'start_date'=>'required|date',
            'end_date'=>'nullable|date|after:start_date',
            'total_installments'=>'nullable|integer|min:1',
            'maturity_amount'=>'nullable|numeric|min:0',
            'notes'=>'nullable|string|max:500',
        ]);
        $data['plan_number'] = CollectionPlan::generatePlanNumber();
        $plan = CollectionPlan::create($data);
        return redirect()->route('collection-plans.show', $plan)->with('success', "Collection Plan {$plan->plan_number} created.");
    }
    public function show(CollectionPlan $collectionPlan) {
        $collectionPlan->load(['customer','entries']);
        return view('collections.show', compact('collectionPlan'));
    }
    public function edit(CollectionPlan $collectionPlan) {
        $customers = Customer::where('status','active')->orderBy('name')->get();
        return view('collections.edit', compact('collectionPlan','customers'));
    }
    public function update(Request $request, CollectionPlan $collectionPlan) {
        $data = $request->validate([
            'plan_name'=>'required|string|max:100',
            'collection_amount'=>'required|numeric|min:1',
            'end_date'=>'nullable|date',
            'total_installments'=>'nullable|integer|min:1',
            'maturity_amount'=>'nullable|numeric|min:0',
            'status'=>'required|in:active,completed,closed',
            'notes'=>'nullable|string|max:500',
        ]);
        $collectionPlan->update($data);
        return redirect()->route('collection-plans.show', $collectionPlan)->with('success', 'Plan updated successfully.');
    }
    public function destroy(CollectionPlan $collectionPlan) {
        $collectionPlan->entries()->delete();
        $collectionPlan->delete();
        return redirect()->route('collection-plans.index')->with('success', 'Plan deleted.');
    }
    public function addEntry(Request $request, CollectionPlan $collectionPlan) {
        $data = $request->validate([
            'amount'=>'required|numeric|min:1',
            'collection_date'=>'required|date',
            'payment_mode'=>'required|in:cash,upi,bank_transfer,cheque',
            'collected_by'=>'nullable|string|max:100',
            'notes'=>'nullable|string|max:300',
        ]);
        $data['collection_plan_id'] = $collectionPlan->id;
        $data['installment_number'] = $collectionPlan->entries()->count() + 1;
        $data['receipt_number'] = CollectionEntry::generateReceiptNumber();
        CollectionEntry::create($data);
        return redirect()->route('collection-plans.show', $collectionPlan)->with('success', 'Collection entry added.');
    }
}
