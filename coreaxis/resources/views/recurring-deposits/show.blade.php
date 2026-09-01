@extends('layouts.banking')
@section('title','RD Details')
@section('content')
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('recurring-deposits.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">RD — {{ $recurringDeposit->rd_number }}</h5>
    <span class="badge bg-{{ $recurringDeposit->getStatusBadge() }} ms-2">{{ ucfirst($recurringDeposit->status) }}</span>
</div>
<div class="row">
<div class="col-lg-5">
<div class="card mb-3"><div class="card-header fw-semibold">RD Details</div><div class="card-body">
    <table class="table table-borderless mb-0">
        <tr><td class="text-muted small">Account</td><td>{{ $recurringDeposit->account?->account_number }}</td></tr>
        <tr><td class="text-muted small">Monthly</td><td class="fw-bold">₹{{ number_format($recurringDeposit->monthly_installment,2) }}</td></tr>
        <tr><td class="text-muted small">Rate</td><td>{{ $recurringDeposit->interest_rate }}% p.a.</td></tr>
        <tr><td class="text-muted small">Tenure</td><td>{{ $recurringDeposit->tenure_months }} months</td></tr>
        <tr><td class="text-muted small">Paid</td><td>{{ $recurringDeposit->installments_paid }}/{{ $recurringDeposit->tenure_months }}</td></tr>
        <tr><td class="text-muted small">Total Deposited</td><td>₹{{ number_format($recurringDeposit->total_deposited,2) }}</td></tr>
        <tr><td class="text-muted small">Next Due</td><td class="fw-semibold text-danger">{{ $recurringDeposit->next_due_date?->format('d M Y') ?? '—' }}</td></tr>
        <tr><td class="text-muted small">Maturity</td><td>{{ $recurringDeposit->maturity_date->format('d M Y') }}</td></tr>
        <tr><td class="text-muted small">Maturity Amt</td><td class="fw-bold text-success">₹{{ number_format($recurringDeposit->maturity_amount,2) }}</td></tr>
    </table>
</div></div>
</div>
<div class="col-lg-7">
<div class="card"><div class="card-header fw-semibold">Installment Schedule</div>
<div class="card-body p-0"><div class="table-responsive">
<table class="table table-sm mb-0">
    <thead class="table-light"><tr><th>#</th><th>Due Date</th><th>Amount</th><th>Status</th><th></th></tr></thead>
    <tbody>
    @foreach($recurringDeposit->installments as $inst)
    <tr>
        <td>{{ $inst->installment_number }}</td>
        <td>{{ $inst->due_date->format('d M Y') }}</td>
        <td>₹{{ number_format($inst->amount,2) }}</td>
        <td><span class="badge bg-{{ $inst->status==='paid'?'success':($inst->status==='missed'?'danger':'warning') }}">{{ ucfirst($inst->status) }}</span>
            @if($inst->paid_date)<br><small class="text-muted">{{ $inst->paid_date->format('d M Y') }}</small>@endif
        </td>
        <td>
            @if($inst->status === 'pending' && $recurringDeposit->status === 'active')
            <form method="POST" action="{{ route('recurring-deposits.pay-installment',[$recurringDeposit,$inst]) }}">@csrf
                <button type="submit" class="btn btn-xs btn-success" style="font-size:.7rem;padding:2px 8px" onclick="return confirm('Pay installment #{{ $inst->installment_number }}?')">Pay</button>
            </form>
            @endif
        </td>
    </tr>
    @endforeach
    </tbody>
</table>
</div></div></div>
</div>
</div>
@endsection
