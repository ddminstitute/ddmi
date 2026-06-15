@extends('layouts.banking')
@section('title','Loan Details')
@section('content')
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('loans.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Loan — {{ $loan->loan_number }}</h5>
    <span class="badge bg-{{ $loan->getStatusBadge() }} ms-2">{{ ucfirst($loan->status) }}</span>
    <a href="{{ route('print.loan.certificate',$loan) }}" target="_blank" class="btn btn-sm btn-outline-secondary ms-auto"><i class="bi bi-printer me-1"></i>Print Certificate</a>
</div>
<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Loan Information</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6"><div class="text-muted small">Customer</div><div class="fw-semibold">{{ $loan->user->name }}</div></div>
                    <div class="col-sm-6"><div class="text-muted small">Account</div><code>{{ $loan->account->account_number }}</code></div>
                    <div class="col-sm-6"><div class="text-muted small">Loan Type</div><div>{{ $loan->getTypeLabel() }}</div></div>
                    <div class="col-sm-6"><div class="text-muted small">Interest Rate</div><div>{{ $loan->interest_rate }}% p.a.</div></div>
                    <div class="col-sm-6"><div class="text-muted small">Tenure</div><div>{{ $loan->tenure_months }} months</div></div>
                    <div class="col-sm-6"><div class="text-muted small">Applied On</div><div>{{ $loan->created_at->format('M d, Y') }}</div></div>
                    @if($loan->disbursed_at)<div class="col-sm-6"><div class="text-muted small">Disbursed On</div><div>{{ $loan->disbursed_at->format('M d, Y') }}</div></div>@endif
                    <div class="col-12"><div class="text-muted small">Purpose</div><div>{{ $loan->purpose ?? '—' }}</div></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-3" style="background:linear-gradient(135deg,#E65100,#F57C00)">
            <div class="card-body text-white">
                <div class="small opacity-75 mb-1">Outstanding Balance</div>
                <div class="display-6 fw-bold">₹{{ number_format($loan->outstanding_amount,2) }}</div>
                <hr class="border-white opacity-25">
                <div class="d-flex justify-content-between small">
                    <span>Principal: ₹{{ number_format($loan->principal_amount,2) }}</span>
                </div>
                <div class="d-flex justify-content-between small">
                    <span>Monthly EMI: ₹{{ number_format($loan->monthly_emi,2) }}</span>
                </div>
                <div class="d-flex justify-content-between small">
                    <span>Total Paid: ₹{{ number_format($loan->paid_amount,2) }}</span>
                </div>
            </div>
        </div>
        @if($loan->status==='pending')
        <div class="d-flex gap-2">
            <form method="POST" action="{{ route('loans.approve',$loan) }}" class="flex-fill">@csrf<button class="btn btn-success w-100"><i class="bi bi-check-lg me-1"></i>Approve</button></form>
            <form method="POST" action="{{ route('loans.reject',$loan) }}" class="flex-fill">@csrf<button class="btn btn-danger w-100"><i class="bi bi-x-lg me-1"></i>Reject</button></form>
        </div>
        @elseif($loan->status==='approved')
        <form method="POST" action="{{ route('loans.disburse',$loan) }}">@csrf<button class="btn btn-info text-white w-100"><i class="bi bi-cash-coin me-1"></i>Disburse Loan</button></form>
        @elseif($loan->status==='active')
        <div class="card">
            <div class="card-header">Make EMI Payment</div>
            <div class="card-body">
                <form method="POST" action="{{ route('loans.payment',$loan) }}">
                    @csrf
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="amount" class="form-control" value="{{ $loan->monthly_emi }}" min="1" step="0.01" required>
                        <button class="btn btn-primary">Pay</button>
                    </div>
                    <div class="form-text">Recommended EMI: ₹{{ number_format($loan->monthly_emi,2) }}</div>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>

@if(count($schedule) > 0)
<div class="card mb-4">
    <div class="card-header"><i class="bi bi-table me-2"></i>Repayment Schedule</div>
    <div class="card-body p-0" style="max-height:400px;overflow-y:auto">
        <table class="table table-sm mb-0">
            <thead class="table-light sticky-top">
                <tr><th>#</th><th>EMI</th><th>Principal</th><th>Interest</th><th>Outstanding</th></tr>
            </thead>
            <tbody>
                @foreach($schedule as $row)
                <tr class="{{ $row['month'] <= $loan->paidInstallments() ? 'table-success' : '' }}">
                    <td>{{ $row['month'] }}</td>
                    <td>₹{{ number_format($row['emi'],2) }}</td>
                    <td>₹{{ number_format($row['principal'],2) }}</td>
                    <td>₹{{ number_format($row['interest'],2) }}</td>
                    <td>₹{{ number_format($row['outstanding'],2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@if($loan->payments->count() > 0)
<div class="card">
    <div class="card-header"><i class="bi bi-receipt me-2"></i>Payment History</div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead class="table-light">
                <tr><th>#</th><th>Reference</th><th>Amount</th><th>Principal</th><th>Interest</th><th>Outstanding After</th><th>Date</th></tr>
            </thead>
            <tbody>
                @foreach($loan->payments as $pay)
                <tr>
                    <td>{{ $pay->payment_number }}</td>
                    <td><code class="small">{{ $pay->reference_number }}</code></td>
                    <td class="fw-semibold">₹{{ number_format($pay->amount,2) }}</td>
                    <td>₹{{ number_format($pay->principal_component,2) }}</td>
                    <td>₹{{ number_format($pay->interest_component,2) }}</td>
                    <td>₹{{ number_format($pay->outstanding_after,2) }}</td>
                    <td class="text-muted small">{{ $pay->payment_date->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
