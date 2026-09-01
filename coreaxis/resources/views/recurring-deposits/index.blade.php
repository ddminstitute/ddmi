@extends('layouts.banking')
@section('title','Recurring Deposits')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-calendar-week me-2 text-primary"></i>Recurring Deposits</h5>
    <a href="{{ route('recurring-deposits.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-circle me-1"></i>Open RD</a>
</div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
<table class="table table-hover mb-0">
    <thead class="table-light"><tr><th>RD Number</th><th>Customer</th><th>Monthly</th><th>Rate</th><th>Paid/Total</th><th>Next Due</th><th>Maturity</th><th>Status</th><th></th></tr></thead>
    <tbody>
    @forelse($rds as $rd)
    <tr>
        <td><code>{{ $rd->rd_number }}</code></td>
        <td>{{ $rd->customer?->name ?? $rd->account?->user?->name }}</td>
        <td class="fw-semibold">₹{{ number_format($rd->monthly_installment,2) }}</td>
        <td>{{ $rd->interest_rate }}%</td>
        <td>{{ $rd->installments_paid }}/{{ $rd->tenure_months }}</td>
        <td>{{ $rd->next_due_date?->format('d M Y') ?? '—' }}</td>
        <td>{{ $rd->maturity_date->format('d M Y') }}</td>
        <td><span class="badge bg-{{ $rd->getStatusBadge() }}">{{ ucfirst($rd->status) }}</span></td>
        <td><a href="{{ route('recurring-deposits.show',$rd) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
    </tr>
    @empty
    <tr><td colspan="9" class="text-center text-muted py-4">No recurring deposits found</td></tr>
    @endforelse
    </tbody>
</table>
</div></div>@if($rds->hasPages())<div class="card-footer">{{ $rds->links() }}</div>@endif</div>
@endsection
