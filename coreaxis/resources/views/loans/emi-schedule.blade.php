@extends('layouts.banking')
@section('title','EMI Schedule — '.$loan->loan_number)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-calendar3 me-2 text-primary"></i>EMI Schedule — {{ $loan->loan_number }}</h5>
    <div class="d-flex gap-2">
        <a href="{{ route('loans.show',$loan) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
        <a href="{{ route('print.loan.certificate',$loan) }}" target="_blank" class="btn btn-outline-primary btn-sm"><i class="bi bi-printer me-1"></i>Print Certificate</a>
    </div>
</div>

{{-- Loan Summary --}}
<div class="row g-3 mb-3">
    <div class="col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#1565C0,#0D47A1)">
            <div class="small opacity-75 mb-1">Loan Amount</div>
            <div class="fs-5 fw-bold">₹{{ number_format($loan->amount,2) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#7B1FA2,#6A1B9A)">
            <div class="small opacity-75 mb-1">Monthly EMI</div>
            <div class="fs-5 fw-bold">₹{{ number_format($loan->emi_amount,2) }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#2E7D32,#1B5E20)">
            <div class="small opacity-75 mb-1">Paid</div>
            <div class="fs-5 fw-bold">{{ $emiSchedules->where('status','paid')->count() }} / {{ $emiSchedules->count() }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#E53935,#B71C1C)">
            <div class="small opacity-75 mb-1">Overdue</div>
            <div class="fs-5 fw-bold">{{ $emiSchedules->where('status','overdue')->count() }}</div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Due Date</th>
                        <th class="text-end">EMI (₹)</th>
                        <th class="text-end">Principal (₹)</th>
                        <th class="text-end">Interest (₹)</th>
                        <th class="text-end">Balance (₹)</th>
                        <th>Status</th>
                        <th>Paid On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($emiSchedules as $emi)
                    <tr class="{{ $emi->status==='overdue' ? 'table-danger' : ($emi->status==='paid' ? 'table-success' : '') }}">
                        <td>{{ $emi->installment_number }}</td>
                        <td>{{ \Carbon\Carbon::parse($emi->due_date)->format('d M Y') }}</td>
                        <td class="text-end fw-semibold">{{ number_format($emi->emi_amount,2) }}</td>
                        <td class="text-end">{{ number_format($emi->principal_amount,2) }}</td>
                        <td class="text-end text-warning">{{ number_format($emi->interest_amount,2) }}</td>
                        <td class="text-end">{{ number_format($emi->balance_after,2) }}</td>
                        <td>
                            @if($emi->status==='paid')
                                <span class="badge bg-success">Paid</span>
                            @elseif($emi->status==='overdue')
                                <span class="badge bg-danger">Overdue</span>
                            @else
                                <span class="badge bg-secondary">Pending</span>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $emi->paid_date ? \Carbon\Carbon::parse($emi->paid_date)->format('d M Y') : '—' }}</td>
                        <td>
                            @if($emi->status !== 'paid')
                            <form method="POST" action="{{ route('loans.pay-emi',[$loan,$emi]) }}" class="d-inline" onsubmit="return confirm('Mark this EMI as paid?')">
                                @csrf
                                <button class="btn btn-xs btn-success" style="font-size:.75rem;padding:.2rem .6rem" title="Mark Paid">
                                    <i class="bi bi-check2"></i> Pay
                                </button>
                            </form>
                            @else
                            <span class="text-muted small">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="2">Total</td>
                        <td class="text-end">₹{{ number_format($emiSchedules->sum('emi_amount'),2) }}</td>
                        <td class="text-end">₹{{ number_format($emiSchedules->sum('principal_amount'),2) }}</td>
                        <td class="text-end text-warning">₹{{ number_format($emiSchedules->sum('interest_amount'),2) }}</td>
                        <td colspan="4"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
