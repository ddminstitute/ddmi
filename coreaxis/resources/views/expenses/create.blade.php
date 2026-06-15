@extends('layouts.banking')
@section('title','Add Expense')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-2 text-primary"></i>Add Expense</h5>
    <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>
<div class="card"><div class="card-body">
<form method="POST" action="{{ route('expenses.store') }}" enctype="multipart/form-data">
@csrf
<div class="row g-3">
    <div class="col-md-4">
        <label>Category *</label>
        <select name="category" class="form-select" required>
            <option value="">Select Category</option>
            @foreach(['Rent','Salary','Electricity','Internet','Stationery','Travel','Marketing','Maintenance','Equipment','Software','Insurance','Legal','Miscellaneous'] as $cat)
            <option value="{{ $cat }}" {{ old('category')==$cat?'selected':'' }}>{{ $cat }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-8"><label>Description *</label><input type="text" name="description" value="{{ old('description') }}" class="form-control" required placeholder="Brief description of expense"></div>
    <div class="col-md-4"><label>Amount (₹) *</label><input type="number" name="amount" value="{{ old('amount') }}" class="form-control" required step="0.01" min="0.01" placeholder="0.00"></div>
    <div class="col-md-4"><label>Expense Date *</label><input type="date" name="expense_date" value="{{ old('expense_date',date('Y-m-d')) }}" class="form-control" required></div>
    <div class="col-md-4"><label>Payment Mode *</label>
        <select name="payment_mode" class="form-select" required>
            <option value="cash">Cash</option>
            <option value="upi">UPI</option>
            <option value="bank_transfer">Bank Transfer</option>
            <option value="cheque">Cheque</option>
        </select>
    </div>
    <div class="col-md-4"><label>Paid To</label><input type="text" name="paid_to" value="{{ old('paid_to') }}" class="form-control" placeholder="Vendor / Person name"></div>
    <div class="col-md-4"><label>Approved By</label><input type="text" name="approved_by" value="{{ old('approved_by') }}" class="form-control" placeholder="Manager name"></div>
    <div class="col-md-4"><label>Receipt File</label><input type="file" name="receipt_file" class="form-control" accept="image/*,application/pdf"></div>
    <div class="col-12"><label>Notes</label><textarea name="notes" class="form-control" rows="2" placeholder="Additional notes...">{{ old('notes') }}</textarea></div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check2 me-2"></i>Add Expense</button>
        <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
    </div>
</div>
</form>
</div></div>
@endsection
