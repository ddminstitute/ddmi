@extends('layouts.print')
@section('print-title','Payslip — '.$payslip->employee->name.' — '.$payslip->getMonthName())
@section('print-content')
<div class="doc-title"><i class="bi bi-file-person me-2"></i>Salary Payslip</div>
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="info-row"><span class="info-label">Employee Name:</span><span class="fw-bold">{{ $payslip->employee->name }}</span></div>
        <div class="info-row"><span class="info-label">Employee ID:</span><span>{{ $payslip->employee->employee_id }}</span></div>
        <div class="info-row"><span class="info-label">Designation:</span><span>{{ $payslip->employee->designation }}</span></div>
        <div class="info-row"><span class="info-label">Department:</span><span>{{ $payslip->employee->department }}</span></div>
    </div>
    <div class="col-md-6 text-md-end">
        <div class="info-row justify-content-md-end"><span class="info-label">Pay Period:</span><span class="fw-bold">{{ $payslip->getMonthName() }} {{ $payslip->year }}</span></div>
        <div class="info-row justify-content-md-end"><span class="info-label">Status:</span><span class="badge bg-{{ $payslip->status==='paid'?'success':'warning' }}">{{ ucfirst($payslip->status) }}</span></div>
        <div class="info-row justify-content-md-end"><span class="info-label">Days Present:</span><span>{{ $payslip->present_days }} / {{ $payslip->working_days }}</span></div>
    </div>
</div>
<hr>
<div class="row g-3">
    <div class="col-md-6">
        <h6 class="fw-bold text-success mb-2"><i class="bi bi-plus-circle me-1"></i>Earnings</h6>
        <table class="table table-sm table-bordered">
            <tr><td>Basic Salary</td><td class="text-end">₹{{ number_format($payslip->basic_salary,2) }}</td></tr>
            @if($payslip->hra)<tr><td>HRA</td><td class="text-end">₹{{ number_format($payslip->hra,2) }}</td></tr>@endif
            @if($payslip->other_allowance)<tr><td>Other Allowances</td><td class="text-end">₹{{ number_format($payslip->other_allowance,2) }}</td></tr>@endif
            <tr class="fw-bold table-success"><td>Gross Salary</td><td class="text-end">₹{{ number_format($payslip->gross_salary,2) }}</td></tr>
        </table>
    </div>
    <div class="col-md-6">
        <h6 class="fw-bold text-danger mb-2"><i class="bi bi-dash-circle me-1"></i>Deductions</h6>
        <table class="table table-sm table-bordered">
            @if($payslip->deductions)
            <tr><td>Total Deductions</td><td class="text-end text-danger">₹{{ number_format($payslip->deductions,2) }}</td></tr>
            @endif
            <tr class="fw-bold table-danger"><td>Net Deductions</td><td class="text-end">₹{{ number_format($payslip->deductions??0,2) }}</td></tr>
        </table>
    </div>
</div>
<div class="border rounded p-3 text-center mt-2" style="background:linear-gradient(135deg,#e8f5e9,#c8e6c9)">
    <div class="text-muted small mb-1">Net Take-Home Salary</div>
    <div style="font-size:2rem;font-weight:900;color:#1b5e20">₹{{ number_format($payslip->net_salary,2) }}</div>
</div>
@if($payslip->notes)
<div class="mt-3"><span class="info-label">Notes:</span> {{ $payslip->notes }}</div>
@endif
<div class="row mt-4">
    <div class="col-6"><div class="border-top pt-3 text-center"><div class="small text-muted">Employee Signature</div></div></div>
    <div class="col-6"><div class="border-top pt-3 text-center"><div class="small text-muted">HR / Authorized Signatory</div></div></div>
</div>
@endsection
