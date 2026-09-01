@extends('layouts.banking')
@section('title','Raise Grievance')
@section('content')
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('grievances.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Raise Grievance / Complaint</h5>
</div>
<div class="row justify-content-center"><div class="col-lg-7"><div class="card"><div class="card-body">
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<form method="POST" action="{{ route('grievances.store') }}">@csrf
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Customer</label><select name="customer_id" class="form-select"><option value="">— None / Walk-in —</option>@foreach($customers as $c)<option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone }})</option>@endforeach</select></div>
    <div class="col-md-6 mb-3"><label class="form-label">Account</label><select name="account_id" class="form-select"><option value="">— Not account-specific —</option>@foreach($accounts as $a)<option value="{{ $a->id }}">{{ $a->account_number }} — {{ $a->customer?->name??$a->user?->name }}</option>@endforeach</select></div>
</div>
<div class="mb-3"><label class="form-label">Subject <span class="text-danger">*</span></label><input type="text" name="subject" class="form-control" value="{{ old('subject') }}" required></div>
<div class="mb-3"><label class="form-label">Description <span class="text-danger">*</span></label><textarea name="description" class="form-control" rows="4" required>{{ old('description') }}</textarea></div>
<div class="row">
    <div class="col-md-4 mb-3"><label class="form-label">Category <span class="text-danger">*</span></label><select name="category" class="form-select" required>@foreach(['transaction','account','loan','service','staff','other'] as $c)<option value="{{ $c }}">{{ ucfirst($c) }}</option>@endforeach</select></div>
    <div class="col-md-4 mb-3"><label class="form-label">Priority <span class="text-danger">*</span></label><select name="priority" class="form-select" required>@foreach(['low','medium','high','urgent'] as $p)<option value="{{ $p }}">{{ ucfirst($p) }}</option>@endforeach</select></div>
    <div class="col-md-4 mb-3"><label class="form-label">Assign To</label><select name="assigned_to" class="form-select"><option value="">— Unassigned —</option>@foreach($staff as $u)<option value="{{ $u->id }}">{{ $u->name }}</option>@endforeach</select></div>
</div>
<div class="d-flex gap-2 justify-content-end"><a href="{{ route('grievances.index') }}" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary">Raise Grievance</button></div>
</form>
</div></div></div></div>
@endsection
