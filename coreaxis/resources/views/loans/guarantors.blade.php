@extends('layouts.banking')
@section('title','Loan Guarantors')
@section('content')
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('loans.show',$loan) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Guarantors — {{ $loan->loan_number }}</h5>
</div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="row">
<div class="col-lg-7">
<div class="card mb-3"><div class="card-header fw-semibold">Add Guarantor</div><div class="card-body">
<form method="POST" action="{{ route('loans.add-guarantor',$loan) }}">@csrf
<div class="row g-2">
    <div class="col-md-6"><input type="text" name="name" class="form-control" placeholder="Full Name *" required></div>
    <div class="col-md-6"><input type="text" name="relation" class="form-control" placeholder="Relation (e.g. Father, Spouse)"></div>
    <div class="col-md-6"><input type="text" name="phone" class="form-control" placeholder="Phone *" required></div>
    <div class="col-md-6"><input type="email" name="email" class="form-control" placeholder="Email"></div>
    <div class="col-12"><input type="text" name="address" class="form-control" placeholder="Address"></div>
    <div class="col-md-6"><input type="text" name="id_proof_type" class="form-control" placeholder="ID Proof Type (Aadhaar, PAN...)"></div>
    <div class="col-md-6"><input type="text" name="id_proof_number" class="form-control" placeholder="ID Proof Number"></div>
    <div class="col-12"><button type="submit" class="btn btn-primary btn-sm">Add Guarantor</button></div>
</div>
</form>
</div></div>
</div>
<div class="col-lg-5">
<div class="card"><div class="card-header fw-semibold">Existing Guarantors</div>
<div class="card-body p-0">
@forelse($guarantors as $g)
<div class="d-flex align-items-start gap-2 p-3 border-bottom">
    <div class="flex-grow-1">
        <div class="fw-semibold">{{ $g->name }} <small class="text-muted">({{ $g->relation }})</small></div>
        <div class="small text-muted">{{ $g->phone }}</div>
        @if($g->id_proof_type)<div class="small text-muted">{{ $g->id_proof_type }}: {{ $g->id_proof_number }}</div>@endif
    </div>
    <form method="POST" action="{{ route('loans.remove-guarantor',[$loan,$g]) }}">@csrf @method('DELETE')
    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove?')"><i class="bi bi-trash"></i></button>
    </form>
</div>
@empty
<div class="text-center text-muted py-4">No guarantors added yet</div>
@endforelse
</div></div>
</div>
</div>
@endsection
