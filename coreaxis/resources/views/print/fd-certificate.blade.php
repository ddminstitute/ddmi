@extends('layouts.banking')
@section('title','FD Maturity Certificate')
@section('content')
@push('styles')
<style>
@media print{
    .topbar,.sidebar,.btn{display:none!important}
    .main-content{margin-left:0!important}
}
.cert-border{border:3px double #1565C0;border-radius:12px;padding:2rem}
.cert-stamp{background:#1565C0;color:#fff;border-radius:50%;width:80px;height:80px;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:2rem}
.watermark{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%) rotate(-35deg);font-size:5rem;opacity:.04;white-space:nowrap;font-weight:900;color:#1565C0;pointer-events:none;z-index:0}
.cert-body{position:relative}
</style>
@endpush
<div class="d-flex align-items-center mb-3 gap-2 no-print">
    <a href="{{ route('fixed-deposits.show',$fixedDeposit) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">FD Maturity Certificate</h5>
    <button onclick="window.print()" class="btn btn-sm btn-primary ms-auto"><i class="bi bi-printer me-1"></i>Print</button>
</div>
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="cert-border cert-body">
    <div class="watermark">COREAXIS FINANCIAL</div>
    <div class="text-center mb-4">
        <div class="cert-stamp"><i class="bi bi-safe2"></i></div>
        <h3 class="fw-bold text-primary mb-1">CoreAxis Financial</h3>
        <div class="text-muted">Fixed Deposit Maturity Certificate</div>
        <div class="badge bg-primary mt-1" style="font-size:.85rem">{{ $fixedDeposit->fd_number }}</div>
    </div>
    <hr>
    <p class="text-center mb-4">This is to certify that the following Fixed Deposit has been issued and is maintained with <strong>CoreAxis Financial</strong>.</p>
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <table class="table table-borderless mb-0">
                <tr><td class="text-muted fw-semibold" style="width:50%">FD Number</td><td><code class="fw-bold">{{ $fixedDeposit->fd_number }}</code></td></tr>
                <tr><td class="text-muted fw-semibold">Depositor Name</td><td class="fw-bold">{{ $fixedDeposit->customer?->name ?? $fixedDeposit->account?->user?->name }}</td></tr>
                <tr><td class="text-muted fw-semibold">Account No.</td><td><code>{{ $fixedDeposit->account?->account_number }}</code></td></tr>
                <tr><td class="text-muted fw-semibold">Start Date</td><td>{{ $fixedDeposit->start_date->format('d M Y') }}</td></tr>
                <tr><td class="text-muted fw-semibold">Maturity Date</td><td class="fw-bold text-primary">{{ $fixedDeposit->maturity_date->format('d M Y') }}</td></tr>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table table-borderless mb-0">
                <tr><td class="text-muted fw-semibold" style="width:55%">Principal Amount</td><td class="fw-bold fs-5 text-primary">₹{{ number_format($fixedDeposit->principal_amount,2) }}</td></tr>
                <tr><td class="text-muted fw-semibold">Interest Rate</td><td>{{ $fixedDeposit->interest_rate }}% p.a.</td></tr>
                <tr><td class="text-muted fw-semibold">Compounding</td><td>{{ ucfirst($fixedDeposit->compounding) }}</td></tr>
                <tr><td class="text-muted fw-semibold">Tenure</td><td>{{ $fixedDeposit->tenure_months }} months</td></tr>
                <tr><td class="text-muted fw-semibold">Maturity Amount</td><td class="fw-bold fs-5 text-success">₹{{ number_format($fixedDeposit->maturity_amount,2) }}</td></tr>
            </table>
        </div>
    </div>
    <div class="alert alert-success text-center">
        <div class="small text-muted">Total Interest Earned</div>
        <div class="fs-4 fw-bold">₹{{ number_format($fixedDeposit->maturity_amount - $fixedDeposit->principal_amount,2) }}</div>
    </div>
    <div class="row mt-4 pt-4 border-top">
        <div class="col-6 text-center">
            <div style="border-top:1px solid #333;display:inline-block;min-width:150px;padding-top:4px" class="mt-5 text-muted small">Depositor Signature</div>
        </div>
        <div class="col-6 text-center">
            <div style="border-top:1px solid #333;display:inline-block;min-width:150px;padding-top:4px" class="mt-5 text-muted small">Authorized Signatory</div>
        </div>
    </div>
    <div class="text-center mt-3 text-muted" style="font-size:.75rem">Issued on {{ now()->format('d M Y') }} &nbsp;&bull;&nbsp; This is a computer-generated certificate.</div>
</div>
</div>
</div>
@endsection
