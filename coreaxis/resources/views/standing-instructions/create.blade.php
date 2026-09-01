@extends('layouts.banking')
@section('title','New Standing Instruction')
@section('content')
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('standing-instructions.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">New Standing Instruction</h5>
</div>
<div class="row justify-content-center"><div class="col-lg-6"><div class="card"><div class="card-body">
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<form method="POST" action="{{ route('standing-instructions.store') }}">@csrf
<div class="mb-3"><label class="form-label">From Account <span class="text-danger">*</span></label><select name="account_id" class="form-select" required><option value="">— Select —</option>@foreach($accounts as $a)<option value="{{ $a->id }}">{{ $a->account_number }} — {{ $a->customer?->name??$a->user?->name }}</option>@endforeach</select></div>
<div class="mb-3"><label class="form-label">Instruction Type <span class="text-danger">*</span></label><select name="instruction_type" class="form-select" required><option value="transfer">Fund Transfer</option><option value="emi_payment">EMI Payment</option><option value="utility">Utility Bill</option><option value="rd_installment">RD Installment</option></select></div>
<div class="mb-3"><label class="form-label">To Account (for transfers)</label><select name="to_account_id" class="form-select"><option value="">— Not applicable —</option>@foreach($accounts as $a)<option value="{{ $a->id }}">{{ $a->account_number }}</option>@endforeach</select></div>
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Amount (₹) <span class="text-danger">*</span></label><div class="input-group"><span class="input-group-text">₹</span><input type="number" name="amount" class="form-control" step="0.01" min="1" required></div></div>
    <div class="col-md-6 mb-3"><label class="form-label">Frequency <span class="text-danger">*</span></label><select name="frequency" class="form-select" required><option value="monthly">Monthly</option><option value="weekly">Weekly</option><option value="quarterly">Quarterly</option><option value="yearly">Yearly</option></select></div>
</div>
<div class="row">
    <div class="col-md-4 mb-3"><label class="form-label">Execution Day (1–28) <span class="text-danger">*</span></label><input type="number" name="execution_day" class="form-control" min="1" max="28" value="1" required></div>
    <div class="col-md-4 mb-3"><label class="form-label">Start Date <span class="text-danger">*</span></label><input type="date" name="start_date" class="form-control" value="{{ date('Y-m-d') }}" required></div>
    <div class="col-md-4 mb-3"><label class="form-label">End Date</label><input type="date" name="end_date" class="form-control"></div>
</div>
<div class="mb-3"><label class="form-label">Description</label><input type="text" name="description" class="form-control" placeholder="Loan EMI, Rent, etc."></div>
<div class="d-flex gap-2 justify-content-end"><a href="{{ route('standing-instructions.index') }}" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary">Create Instruction</button></div>
</form>
</div></div></div></div>
@endsection
