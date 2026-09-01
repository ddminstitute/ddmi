<?php
namespace App\Http\Controllers;
use App\Models\CompanyExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller {
    public function index(Request $request) {
        $query = CompanyExpense::query();
        if ($request->category) $query->where('category', $request->category);
        if ($request->from_date) $query->whereDate('expense_date','>=',$request->from_date);
        if ($request->to_date) $query->whereDate('expense_date','<=',$request->to_date);
        $expenses = $query->latest('expense_date')->paginate(20)->withQueryString();
        $totalThisMonth = CompanyExpense::whereMonth('expense_date', now()->month)->whereYear('expense_date', now()->year)->sum('amount');
        $categories = CompanyExpense::distinct()->pluck('category');
        return view('expenses.index', compact('expenses','totalThisMonth','categories'));
    }
    public function create() { return view('expenses.create'); }
    public function store(Request $request) {
        $data = $request->validate([
            'category'=>'required|string|max:100',
            'description'=>'required|string|max:200',
            'amount'=>'required|numeric|min:0.01',
            'expense_date'=>'required|date',
            'payment_mode'=>'required|in:cash,upi,bank_transfer,cheque',
            'paid_to'=>'nullable|string|max:100',
            'approved_by'=>'nullable|string|max:100',
            'notes'=>'nullable|string|max:300',
            'receipt_file'=>'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
        $data['expense_number'] = CompanyExpense::generateExpenseNumber();
        if ($request->hasFile('receipt_file')) {
            $data['receipt_file'] = $request->file('receipt_file')->store('expenses','public');
        }
        CompanyExpense::create($data);
        return redirect()->route('expenses.index')->with('success', 'Expense recorded.');
    }
    public function edit(CompanyExpense $expense) { return view('expenses.edit', compact('expense')); }
    public function update(Request $request, CompanyExpense $expense) {
        $data = $request->validate([
            'category'=>'required|string|max:100',
            'description'=>'required|string|max:200',
            'amount'=>'required|numeric|min:0.01',
            'expense_date'=>'required|date',
            'payment_mode'=>'required|in:cash,upi,bank_transfer,cheque',
            'paid_to'=>'nullable|string|max:100',
            'approved_by'=>'nullable|string|max:100',
            'notes'=>'nullable|string|max:300',
        ]);
        $expense->update($data);
        return redirect()->route('expenses.index')->with('success', 'Expense updated.');
    }
    public function destroy(CompanyExpense $expense) {
        if ($expense->receipt_file) Storage::disk('public')->delete($expense->receipt_file);
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted.');
    }
}
