@extends('layouts.banking')
@section('title','FD Details')
@section('content')
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('fixed-deposits.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">FD — {{ $fixedDeposit->fd_number }}</h5>
    <span class="badge bg-{{ $fixedDeposit->getStatusBadge() }} ms-2">{{ ucfirst(str_replace('_',' ',$fixedDeposit->status)) }}</span>
</div>
<div class="row"><div class="col-lg-8">
<div class="card"><div class="card-body">
    <div class="text-center mb-4">
        <div class="display-5 fw-bold text-primary">₹{{ number_format($fixedDeposit->principal_amount,2) }}</div>
        <div class="text-muted">Principal Amount</div>
    </div>
    <table class="table table-borderless">
        <tr><td class="text-muted">FD Number</td><td><code>{{ $fixedDeposit->fd_number }}</code></td></tr>
        <tr><td class="text-muted">Account</td><td>{{ $fixedDeposit->account?->account_number }}</td></tr>
        <tr><td class="text-muted">Customer</td><td>{{ $fixedDeposit->customer?->name ?? $fixedDeposit->account?->user?->name }}</td></tr>
        <tr><td class="text-muted">Interest Rate</td><td>{{ $fixedDeposit->interest_rate }}% p.a. ({{ ucfirst($fixedDeposit->compounding) }})</td></tr>
        <tr><td class="text-muted">Tenure</td><td>{{ $fixedDeposit->tenure_months }} months</td></tr>
        <tr><td class="text-muted">Start Date</td><td>{{ $fixedDeposit->start_date->format('d M Y') }}</td></tr>
        <tr><td class="text-muted">Maturity Date</td><td class="fw-semibold">{{ $fixedDeposit->maturity_date->format('d M Y') }}
            @if($daysToMaturity > 0)<span class="badge bg-info ms-1">{{ $daysToMaturity }} days left</span>
            @elseif($fixedDeposit->status === 'active')<span class="badge bg-danger ms-1">Matured</span>
            @endif
        </td></tr>
        <tr><td class="text-muted">Maturity Amount</td><td class="fw-bold text-success fs-5">₹{{ number_format($fixedDeposit->maturity_amount,2) }}</td></tr>
        <tr><td class="text-muted">Interest Earned</td><td class="text-warning">₹{{ number_format($fixedDeposit->maturity_amount - $fixedDeposit->principal_amount,2) }}</td></tr>
        <tr><td class="text-muted">Auto Renew</td><td>{{ $fixedDeposit->auto_renew ? 'Yes' : 'No' }}</td></tr>
    </table>
    @if($fixedDeposit->status === 'active')
    <div class="mt-3" data-bs-toggle="modal" data-bs-target="#closeModal">
        <button class="btn btn-outline-danger"><i class="bi bi-x-circle me-1"></i>Close FD</button>
    </div>
    <div class="modal fade" id="closeModal" tabindex="-1">
        <div class="modal-dialog"><div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Close FD {{ $fixedDeposit->fd_number }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form method="POST" action="{{ route('fixed-deposits.close',$fixedDeposit) }}">@csrf
            <div class="modal-body">
                @if(now()->lt($fixedDeposit->maturity_date))<div class="alert alert-warning">Premature closure — {{ $fixedDeposit->premature_penalty_percent }}% penalty on interest will apply.</div>@endif
                <div class="mb-3"><label class="form-label">Reason *</label><input type="text" name="closure_reason" class="form-control" required></div>
            </div>
            <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-danger">Confirm Close</button></div>
            </form>
        </div></div>
    </div>
    @endif
</div></div>
</div></div>
@endsection
