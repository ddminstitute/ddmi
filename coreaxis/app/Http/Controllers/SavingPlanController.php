<?php
namespace App\Http\Controllers;
use App\Models\SavingPlan;
use Illuminate\Http\Request;

class SavingPlanController extends Controller {
    public function index() {
        $plans = SavingPlan::latest()->paginate(15);
        return view('saving-plans.index', compact('plans'));
    }
    public function create() { return view('saving-plans.create'); }
    public function store(Request $request) {
        $data = $request->validate([
            'plan_name'=>'required|string|max:100',
            'plan_type'=>'required|in:daily,weekly,monthly,yearly',
            'minimum_amount'=>'required|numeric|min:0',
            'interest_rate'=>'required|numeric|min:0|max:100',
            'tenure_months'=>'nullable|integer|min:1',
            'description'=>'nullable|string|max:500',
        ]);
        $data['plan_code'] = SavingPlan::generatePlanCode();
        $data['is_active'] = true;
        SavingPlan::create($data);
        return redirect()->route('saving-plans.index')->with('success', 'Saving plan created.');
    }
    public function show(SavingPlan $savingPlan) { return view('saving-plans.show', compact('savingPlan')); }
    public function edit(SavingPlan $savingPlan) { return view('saving-plans.edit', compact('savingPlan')); }
    public function update(Request $request, SavingPlan $savingPlan) {
        $data = $request->validate([
            'plan_name'=>'required|string|max:100',
            'plan_type'=>'required|in:daily,weekly,monthly,yearly',
            'minimum_amount'=>'required|numeric|min:0',
            'interest_rate'=>'required|numeric|min:0|max:100',
            'tenure_months'=>'nullable|integer|min:1',
            'description'=>'nullable|string|max:500',
            'is_active'=>'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $savingPlan->update($data);
        return redirect()->route('saving-plans.index')->with('success', 'Saving plan updated.');
    }
    public function destroy(SavingPlan $savingPlan) {
        $savingPlan->delete();
        return redirect()->route('saving-plans.index')->with('success', 'Saving plan deleted.');
    }
}
