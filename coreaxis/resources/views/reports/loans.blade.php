@extends('layouts.banking')
@section('title','Loan Portfolio Report')
@section('content')
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Loan Portfolio Report</h5>
</div>
<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card border-primary"><div class="card-body text-center"><div class="text-muted small">Total Applied</div><div class="fs-4 fw-bold text-primary">${{ number_format($summary['total_principal'],2) }}</div></div></div></div>
    <div class="col-md-3"><div class="card border-success"><div class="card-body text-center"><div class="text-muted small">Disbursed</div><div class="fs-4 fw-bold text-success">${{ number_format($summary['total_disbursed'],2) }}</div></div></div></div>
    <div class="col-md-3"><div class="card border-danger"><div class="card-body text-center"><div class="text-muted small">Outstanding</div><div class="fs-4 fw-bold text-danger">${{ number_format($summary['total_outstanding'],2) }}</div></div></div></div>
    <div class="col-md-3"><div class="card border-warning"><div class="card-body text-center"><div class="text-muted small">Collected</div><div class="fs-4 fw-bold text-warning">${{ number_format($summary['total_collected'],2) }}</div></div></div></div>
</div>
<div class="card mb-3">
    <div class="card-body py-2">
        <form class="row g-2" method="GET">
            <div class="col-md-3"><select name="status" class="form-select form-select-sm"><option value="">All Status</option><option value="pending">Pending</option><option value="active">Active</option><option value="closed">Closed</option><option value="rejected">Rejected</option></select></div>
            <div class="col-md-3"><select name="type" class="form-select form-select-sm"><option value="">All Types</option><option value="personal">Personal</option><option value="home">Home</option><option value="auto">Auto</option><option value="business">Business</option></select></div>
            <div class="col-auto"><button class="btn btn-sm btn-primary">Filter</button></div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Loan No.</th><th>Customer</th><th>Type</th><th>Principal</th><th>Rate</th><th>EMI</th><th>Outstanding</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse($loans as $loan)
                    <tr>
                        <td><a href="{{ route('loans.show',$loan) }}"><code>{{ $loan->loan_number }}</code></a></td>
                        <td>{{ $loan->user->name }}</td>
                        <td>{{ $loan->getTypeLabel() }}</td>
                        <td>${{ number_format($loan->principal_amount,2) }}</td>
                        <td>{{ $loan->interest_rate }}%</td>
                        <td>${{ number_format($loan->monthly_emi,2) }}</td>
                        <td>${{ number_format($loan->outstanding_amount,2) }}</td>
                        <td><span class="badge bg-{{ $loan->getStatusBadge() }}">{{ ucfirst($loan->status) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">No loans found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($loans->hasPages())<div class="card-footer">{{ $loans->links() }}</div>@endif
</div>
@endsection
