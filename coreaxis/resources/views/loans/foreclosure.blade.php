@extends('layouts.banking')
@section('title','Loan Foreclosure')
@section('content')
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('loans.show',$loan) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Foreclosure — {{ $loan->loan_number }}</h5>
</div>
<div class="row justify-content-center"><div class="col-lg-6">
<div class="card border-warning"><div class="card-header bg-warning text-dark fw-semibold"><i class="bi bi-exclamation-triangle me-2"></i>Loan Foreclosure Confirmation</div>
<div class="card-body">
<table class="table table-borderless">
    <tr><td class="text-muted">Outstanding Principal</td><td class="fw-semibold">₹{{ number_format($loan->outstanding_amount,2) }}</td></tr>
    <tr><td class="text-muted">Foreclosure Charges (2%)</td><td class="text-danger">₹{{ number_format($charges,2) }}</td></tr>
    <tr class="table-warning"><td class="fw-bold">Total Amount Payable</td><td class="fw-bold fs-5">₹{{ number_format($totalPayable,2) }}</td></tr>
</table>
<div class="alert alert-info small"><i class="bi bi-info-circle me-2"></i>This will close the loan, mark all remaining EMIs as waived, and debit ₹{{ number_format($totalPayable,2) }} from account {{ $loan->account?->account_number }}. A No-Dues Certificate can be printed after foreclosure.</div>
<form method="POST" action="{{ route('loans.foreclose',$loan) }}">@csrf
    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="confirm" value="1" id="confirmCheck" required>
        <label class="form-check-label" for="confirmCheck">I confirm the above foreclosure details and authorize the debit.</label>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('loans.show',$loan) }}" class="btn btn-outline-secondary flex-grow-1">Cancel</a>
        <button type="submit" class="btn btn-danger flex-grow-1">Foreclose Loan</button>
    </div>
</form>
</div></div>
</div></div>
@endsection
