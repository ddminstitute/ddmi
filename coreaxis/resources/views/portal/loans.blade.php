@extends('layouts.portal')
@section('title','My Loans')
@section('content')
<h5 class="fw-bold mb-3"><i class="bi bi-cash-coin me-2 text-warning"></i>My Loans</h5>
@forelse($loans as $loan)
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center"><span class="fw-semibold">{{ $loan->loan_number }}</span><span class="badge bg-{{ $loan->getStatusBadge() }}">{{ ucfirst($loan->status) }}</span></div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-sm-4"><div class="text-muted small">Loan Type</div><div>{{ $loan->getTypeLabel() }}</div></div>
            <div class="col-sm-4"><div class="text-muted small">Principal</div><div class="fw-semibold">₹{{ number_format($loan->principal_amount,2) }}</div></div>
            <div class="col-sm-4"><div class="text-muted small">Interest Rate</div><div>{{ $loan->interest_rate }}% p.a.</div></div>
            <div class="col-sm-4"><div class="text-muted small">Tenure</div><div>{{ $loan->tenure_months }} months</div></div>
            <div class="col-sm-4"><div class="text-muted small">Monthly EMI</div><div class="fw-semibold text-primary">₹{{ number_format($loan->monthly_emi,2) }}</div></div>
            <div class="col-sm-4"><div class="text-muted small">Outstanding</div><div class="fw-bold text-danger">₹{{ number_format($loan->outstanding_amount,2) }}</div></div>
        </div>
        @if($loan->status==='active')
        @php $paid=$loan->paidInstallments();$total=$loan->tenure_months;$pct=$total>0?round($paid/$total*100):0; @endphp
        <div class="mt-3"><div class="d-flex justify-content-between small text-muted mb-1"><span>Repayment Progress</span><span>{{ $paid }} / {{ $total }} EMIs paid</span></div><div class="progress" style="height:8px"><div class="progress-bar bg-success" style="width:{{ $pct }}%"></div></div></div>
        @endif
    </div>
</div>
@empty
<div class="alert alert-info"><i class="bi bi-info-circle me-2"></i>You have no loans on record.</div>
@endforelse
@endsection
