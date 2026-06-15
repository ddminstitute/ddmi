@extends('layouts.banking')
@section('title','Loans')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold">Loan Management</h5>
    <a href="{{ route('loans.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Apply for Loan</a>
</div>
<div class="card mb-3">
    <div class="card-body py-2">
        <form class="row g-2" method="GET">
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                    <option value="approved" {{ request('status')=='approved'?'selected':'' }}>Approved</option>
                    <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
                    <option value="rejected" {{ request('status')=='rejected'?'selected':'' }}>Rejected</option>
                    <option value="closed" {{ request('status')=='closed'?'selected':'' }}>Closed</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    <option value="personal" {{ request('type')=='personal'?'selected':'' }}>Personal</option>
                    <option value="home" {{ request('type')=='home'?'selected':'' }}>Home</option>
                    <option value="auto" {{ request('type')=='auto'?'selected':'' }}>Auto</option>
                    <option value="business" {{ request('type')=='business'?'selected':'' }}>Business</option>
                </select>
            </div>
            <div class="col-auto"><button class="btn btn-sm btn-primary">Filter</button> <a href="{{ route('loans.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a></div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Loan No.</th><th>Customer</th><th>Type</th><th>Principal</th><th>EMI</th><th>Outstanding</th><th>Status</th><th>Applied</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($loans as $loan)
                    <tr>
                        <td><code>{{ $loan->loan_number }}</code></td>
                        <td>{{ $loan->user->name }}</td>
                        <td>{{ $loan->getTypeLabel() }}</td>
                        <td>${{ number_format($loan->principal_amount,2) }}</td>
                        <td>${{ number_format($loan->monthly_emi,2) }}</td>
                        <td class="{{ $loan->status==='active'?'text-danger fw-semibold':'' }}">${{ number_format($loan->outstanding_amount,2) }}</td>
                        <td><span class="badge bg-{{ $loan->getStatusBadge() }}">{{ ucfirst($loan->status) }}</span></td>
                        <td class="text-muted small">{{ $loan->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('loans.show',$loan) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                            @if($loan->status==='pending')
                            <form method="POST" action="{{ route('loans.approve',$loan) }}" class="d-inline">@csrf<button class="btn btn-sm btn-success ms-1"><i class="bi bi-check"></i></button></form>
                            <form method="POST" action="{{ route('loans.reject',$loan) }}" class="d-inline">@csrf<button class="btn btn-sm btn-danger ms-1"><i class="bi bi-x"></i></button></form>
                            @elseif($loan->status==='approved')
                            <form method="POST" action="{{ route('loans.disburse',$loan) }}" class="d-inline">@csrf<button class="btn btn-sm btn-info text-white ms-1">Disburse</button></form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center text-muted py-5">No loans found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($loans->hasPages())
    <div class="card-footer">{{ $loans->links() }}</div>
    @endif
</div>
@endsection
