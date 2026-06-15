@extends('layouts.banking')
@section('title','Collection Plans')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-collection me-2 text-primary"></i>Collection Plans</h5>
    <a href="{{ route('collection-plans.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle me-1"></i>New Plan</a>
</div>
<div class="card mb-3"><div class="card-body py-2">
    <form method="GET" class="row g-2">
        <div class="col-md-3"><input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search plan no / customer..."></div>
        <div class="col-md-2"><select name="type" class="form-select form-select-sm"><option value="">All Types</option><option value="daily" {{ request('type')=='daily'?'selected':'' }}>Daily</option><option value="weekly" {{ request('type')=='weekly'?'selected':'' }}>Weekly</option><option value="monthly" {{ request('type')=='monthly'?'selected':'' }}>Monthly</option></select></div>
        <div class="col-md-2"><select name="status" class="form-select form-select-sm"><option value="">All Status</option><option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option><option value="completed" {{ request('status')=='completed'?'selected':'' }}>Completed</option><option value="closed" {{ request('status')=='closed'?'selected':'' }}>Closed</option></select></div>
        <div class="col-auto"><button class="btn btn-primary btn-sm" type="submit"><i class="bi bi-search me-1"></i>Search</button></div>
    </form>
</div></div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead class="table-light"><tr><th>Plan No</th><th>Customer</th><th>Plan Name</th><th>Type</th><th>Amount</th><th>Installments</th><th>Start Date</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
        @forelse($plans as $p)
        <tr>
            <td class="fw-semibold small">{{ $p->plan_number }}</td>
            <td><div class="fw-semibold small">{{ $p->customer->name }}</div><div class="text-muted" style="font-size:.72rem">{{ $p->customer->customer_id }}</div></td>
            <td class="small">{{ $p->plan_name }}</td>
            <td><span class="badge bg-{{ $p->getTypeBadge() }}">{{ ucfirst($p->collection_type) }}</span></td>
            <td class="fw-semibold">₹{{ number_format($p->collection_amount,2) }}</td>
            <td class="small">{{ $p->paidInstallments() }} / {{ $p->total_installments ?? '∞' }}</td>
            <td class="small">{{ $p->start_date->format('d M Y') }}</td>
            <td><span class="badge bg-{{ $p->status==='active'?'success':($p->status==='completed'?'primary':'secondary') }}">{{ ucfirst($p->status) }}</span></td>
            <td>
                <a href="{{ route('collection-plans.show',$p) }}" class="btn btn-xs btn-outline-primary me-1" style="font-size:.75rem;padding:.2rem .55rem"><i class="bi bi-eye"></i></a>
                <a href="{{ route('collection-plans.edit',$p) }}" class="btn btn-xs btn-outline-secondary" style="font-size:.75rem;padding:.2rem .55rem"><i class="bi bi-pencil"></i></a>
            </td>
        </tr>
        @empty
        <tr><td colspan="9" class="text-center text-muted py-4">No collection plans found</td></tr>
        @endforelse
    </tbody>
</table>
</div></div>
@if($plans->hasPages())<div class="card-footer bg-white border-0 py-2">{{ $plans->links() }}</div>@endif
</div>
@endsection
