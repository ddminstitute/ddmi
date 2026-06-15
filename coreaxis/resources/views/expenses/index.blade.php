@extends('layouts.banking')
@section('title','Company Expenses')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-receipt-cutoff me-2 text-primary"></i>Company Expenses</h5>
    <a href="{{ route('expenses.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle me-1"></i>Add Expense</a>
</div>
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="stat-card" style="background:linear-gradient(135deg,#E53935,#EF5350)">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="small opacity-75 mb-1">This Month Total</div><div class="fs-4 fw-bold">₹{{ number_format($totalThisMonth,2) }}</div></div>
                <div class="stat-icon"><i class="bi bi-receipt"></i></div>
            </div>
        </div>
    </div>
</div>
<div class="card mb-3"><div class="card-body py-2">
    <form method="GET" class="row g-2">
        <div class="col-md-3">
            <select name="category" class="form-select form-select-sm">
                <option value="">All Categories</option>
                @foreach($categories as $cat)<option value="{{ $cat }}" {{ request('category')==$cat?'selected':'' }}>{{ $cat }}</option>@endforeach
            </select>
        </div>
        <div class="col-md-3"><input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control form-control-sm" placeholder="From date"></div>
        <div class="col-md-3"><input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control form-control-sm" placeholder="To date"></div>
        <div class="col-auto"><button class="btn btn-primary btn-sm" type="submit"><i class="bi bi-search me-1"></i>Filter</button></div>
        @if(request()->anyFilled(['category','from_date','to_date']))
        <div class="col-auto"><a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a></div>
        @endif
    </form>
</div></div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead class="table-light"><tr><th>Expense No</th><th>Date</th><th>Category</th><th>Description</th><th>Amount</th><th>Mode</th><th>Paid To</th><th>Actions</th></tr></thead>
    <tbody>
        @forelse($expenses as $exp)
        <tr>
            <td class="fw-semibold small">{{ $exp->expense_number }}</td>
            <td class="small">{{ $exp->expense_date->format('d M Y') }}</td>
            <td><span class="badge bg-secondary">{{ $exp->category }}</span></td>
            <td class="small">{{ Str::limit($exp->description,40) }}</td>
            <td class="fw-bold text-danger">₹{{ number_format($exp->amount,2) }}</td>
            <td class="small">{{ ucfirst(str_replace('_',' ',$exp->payment_mode)) }}</td>
            <td class="small text-muted">{{ $exp->paid_to ?? '—' }}</td>
            <td>
                <a href="{{ route('expenses.edit',$exp) }}" class="btn btn-xs btn-outline-secondary me-1" style="font-size:.75rem;padding:.2rem .55rem"><i class="bi bi-pencil"></i></a>
                <form method="POST" action="{{ route('expenses.destroy',$exp) }}" class="d-inline" onsubmit="return confirm('Delete this expense?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-xs btn-outline-danger" style="font-size:.75rem;padding:.2rem .55rem"><i class="bi bi-trash"></i></button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center text-muted py-4">No expenses found</td></tr>
        @endforelse
    </tbody>
</table>
</div></div>
@if($expenses->hasPages())<div class="card-footer bg-white border-0 py-2">{{ $expenses->links() }}</div>@endif
</div>
@endsection
