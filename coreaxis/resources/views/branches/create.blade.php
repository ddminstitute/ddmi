@extends('layouts.banking')
@section('title','Add Branch')
@section('content')
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('branches.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Add Branch</h5>
</div>
<div class="row justify-content-center"><div class="col-lg-6"><div class="card"><div class="card-body">
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<form method="POST" action="{{ route('branches.store') }}">@csrf
<div class="row">
    <div class="col-md-4 mb-3"><label class="form-label">Branch Code <span class="text-danger">*</span></label><input type="text" name="branch_code" class="form-control" placeholder="HQ001" required></div>
    <div class="col-md-8 mb-3"><label class="form-label">Branch Name <span class="text-danger">*</span></label><input type="text" name="branch_name" class="form-control" required></div>
</div>
<div class="mb-3"><label class="form-label">Address</label><input type="text" name="address" class="form-control"></div>
<div class="row">
    <div class="col-md-4 mb-3"><label class="form-label">City</label><input type="text" name="city" class="form-control"></div>
    <div class="col-md-4 mb-3"><label class="form-label">State</label><input type="text" name="state" class="form-control"></div>
    <div class="col-md-4 mb-3"><label class="form-label">Pincode</label><input type="text" name="pincode" class="form-control" maxlength="6"></div>
</div>
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control"></div>
    <div class="col-md-6 mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control"></div>
</div>
<div class="mb-3"><label class="form-label">Branch Manager Name</label><input type="text" name="manager_name" class="form-control"></div>
<div class="d-flex gap-2 justify-content-end"><a href="{{ route('branches.index') }}" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary">Save Branch</button></div>
</form>
</div></div></div></div>
@endsection
