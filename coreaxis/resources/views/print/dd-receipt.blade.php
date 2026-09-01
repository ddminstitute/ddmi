@extends('layouts.banking')
@section('title','Demand Draft Receipt')
@section('content')
@push('styles')
<style>
@media print{
    .topbar,.sidebar,.btn{display:none!important}
    .main-content{margin-left:0!important}
}
</style>
@endpush
<div class="d-flex align-items-center mb-3 gap-2 no-print">
    <a href="{{ route('demand-drafts.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">DD / Pay Order Receipt</h5>
    <button onclick="window.print()" class="btn btn-sm btn-primary ms-auto"><i class="bi bi-printer me-1"></i>Print</button>
</div>
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <div class="fw-bold text-primary" style="font-size:1rem">CoreAxis Financial</div>
            <div class="small text-muted">{{ ucwords(str_replace('_',' ',$demandDraft->instrument_type)) }} Receipt</div>
        </div>
        <div class="text-end">
            <code class="fw-bold">{{ $demandDraft->dd_number }}</code><br>
            <span class="badge bg-{{ $demandDraft->status==='active'?'success':($demandDraft->status==='cancelled'?'danger':'secondary') }}">{{ ucfirst($demandDraft->status) }}</span>
        </div>
    </div>
    <div class="card-body">
        <div class="text-center mb-4">
            <div class="display-4 fw-bold text-primary">₹{{ number_format($demandDraft->amount,2) }}</div>
            <div class="text-muted">Amount in Words: <strong>{{ $amountInWords ?? '' }}</strong></div>
        </div>
        <table class="table table-borderless">
            <tr><td class="text-muted fw-semibold" style="width:40%">DD / PO Number</td><td><code class="fw-bold">{{ $demandDraft->dd_number }}</code></td></tr>
            <tr><td class="text-muted fw-semibold">Instrument Type</td><td>{{ ucwords(str_replace('_',' ',$demandDraft->instrument_type)) }}</td></tr>
            <tr><td class="text-muted fw-semibold">Payee Name</td><td class="fw-bold">{{ $demandDraft->payee_name }}</td></tr>
            <tr><td class="text-muted fw-semibold">Payable At</td><td>{{ $demandDraft->payable_at_city ?? '—' }}</td></tr>
            <tr><td class="text-muted fw-semibold">Payable At Bank</td><td>{{ $demandDraft->payable_at_bank ?? '—' }}</td></tr>
            <tr><td class="text-muted fw-semibold">Drawn from Account</td><td><code>{{ $demandDraft->account?->account_number }}</code></td></tr>
            <tr><td class="text-muted fw-semibold">Principal Amount</td><td class="fw-bold">₹{{ number_format($demandDraft->amount,2) }}</td></tr>
            <tr><td class="text-muted fw-semibold">Charges</td><td>₹{{ number_format($demandDraft->charges,2) }}</td></tr>
            <tr><td class="text-muted fw-semibold">Total Debited</td><td class="fw-bold text-danger">₹{{ number_format($demandDraft->amount + $demandDraft->charges,2) }}</td></tr>
            <tr><td class="text-muted fw-semibold">Issue Date</td><td>{{ $demandDraft->issue_date->format('d M Y') }}</td></tr>
            <tr><td class="text-muted fw-semibold">Valid Until</td><td>{{ $demandDraft->valid_until?->format('d M Y') ?? '3 months from issue' }}</td></tr>
        </table>
        @if($demandDraft->status==='cancelled')
        <div class="alert alert-danger text-center fw-bold mt-2">CANCELLED — {{ $demandDraft->cancellation_reason }}</div>
        @endif
        <div class="row mt-4 pt-3 border-top">
            <div class="col-6 text-center">
                <div style="border-top:1px solid #333;display:inline-block;min-width:130px;padding-top:4px" class="mt-5 text-muted small">Applicant Signature</div>
            </div>
            <div class="col-6 text-center">
                <div style="border-top:1px solid #333;display:inline-block;min-width:130px;padding-top:4px" class="mt-5 text-muted small">Bank Officer Signature</div>
            </div>
        </div>
    </div>
    <div class="card-footer text-center text-muted" style="font-size:.75rem">Issued by CoreAxis Financial on {{ now()->format('d M Y H:i') }} &bull; Computer-generated document.</div>
</div>
</div>
</div>
@endsection
