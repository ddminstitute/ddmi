@extends('layouts.banking')
@section('title','Saving Plans')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-piggy-bank me-2 text-primary"></i>Saving Plans</h5>
    <a href="{{ route('saving-plans.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle me-1"></i>New Plan</a>
</div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead class="table-light"><tr><th>Code</th><th>Plan Name</th><th>Type</th><th>Min. Amount</th><th>Interest Rate</th><th>Tenure</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
        @forelse($plans as $p)
        <tr>
            <td class="fw-semibold small">{{ $p->plan_code }}</td>
            <td class="fw-semibold">{{ $p->plan_name }}<div class="text-muted small">{{ Str::limit($p->description,50) }}</div></td>
            <td><span class="badge bg-{{ $p->getTypeBadge() }}">{{ ucfirst($p->plan_type) }}</span></td>
            <td class="fw-semibold">₹{{ number_format($p->minimum_amount,2) }}</td>
            <td><span class="text-success fw-bold">{{ $p->interest_rate }}%</span> p.a.</td>
            <td class="small">{{ $p->tenure_months ? $p->tenure_months.' months' : 'Open' }}</td>
            <td><span class="badge bg-{{ $p->is_active?'success':'secondary' }}">{{ $p->is_active?'Active':'Inactive' }}</span></td>
            <td>
                <a href="{{ route('saving-plans.show',$p) }}" class="btn btn-xs btn-outline-primary me-1" style="font-size:.75rem;padding:.2rem .55rem"><i class="bi bi-eye"></i></a>
                <a href="{{ route('saving-plans.edit',$p) }}" class="btn btn-xs btn-outline-secondary me-1" style="font-size:.75rem;padding:.2rem .55rem"><i class="bi bi-pencil"></i></a>
                <form method="POST" action="{{ route('saving-plans.destroy',$p) }}" class="d-inline" onsubmit="return confirm('Delete this plan?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-xs btn-outline-danger" style="font-size:.75rem;padding:.2rem .55rem"><i class="bi bi-trash"></i></button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center text-muted py-4">No saving plans yet</td></tr>
        @endforelse
    </tbody>
</table>
</div></div></div>
@endsection
