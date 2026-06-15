@extends('layouts.banking')
@section('title','Edit Expense')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-pencil me-2 text-primary"></i>Edit Expense — {{ $expense->expense_number }}</h5>
    <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>
<div class="card"><div class="card-body">
<form method="POST" action="{{ route('expenses.update',$expense) }}">
@csrf @method('PUT')
<div class="row g-3">
    <div class="col-md-4"><label>Category *</label>
        <select name="category" class="form-select" required>
            @foreach(['Rent','Salary','Electricity','Internet','Stationery','Travel','Marketing','Maintenance','Equipment','Software','Insurance','Legal','Miscellaneous'] as $cat)
            <option value="{{ $cat }}" {{ old('category',$expense->category)==$cat?'selected':'' }}>{{ $cat }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-8"><label>Description *</label><input type="text" name="description" value="{{ old('description',$expense->description) }}" class="form-control" required></div>
    <div class="col-md-3"><label>Amount (₹) *</label><input type="number" name="amount" value="{{ old('amount',$expense->amount) }}" class="form-control" required step="0.01"></div>
    <div class="col-md-3"><label>Date *</label><input type="date" name="expense_date" value="{{ old('expense_date',$expense->expense_date->format('Y-m-d')) }}" class="form-control" required></div>
    <div class="col-md-3"><label>Payment Mode *</label>
        <select name="payment_mode" class="form-select" required>
            @foreach(['cash','upi','bank_transfer','cheque'] as $m)
            <option value="{{ $m }}" {{ old('payment_mode',$expense->payment_mode)==$m?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$m)) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3"><label>Paid To</label><input type="text" name="paid_to" value="{{ old('paid_to',$expense->paid_to) }}" class="form-control"></div>
    <div class="col-md-3"><label>Approved By</label><input type="text" name="approved_by" value="{{ old('approved_by',$expense->approved_by) }}" class="form-control"></div>
    <div class="col-12"><label>Notes</label><textarea name="notes" class="form-control" rows="2">{{ old('notes',$expense->notes) }}</textarea></div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check2 me-2"></i>Update Expense</button>
        <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
    </div>
</div>
</form>
</div></div>
@endsection
