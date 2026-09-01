@extends('layouts.banking')
@section('title','Edit Branch')
@section('content')
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('branches.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Edit Branch — {{ $branch->branch_name }}</h5>
</div>
<div class="row justify-content-center"><div class="col-lg-6"><div class="card"><div class="card-body">
<form method="POST" action="{{ route('branches.update',$branch) }}">@csrf @method('PUT')
<div class="mb-3"><label class="form-label">Branch Name <span class="text-danger">*</span></label><input type="text" name="branch_name" class="form-control" value="{{ old('branch_name',$branch->branch_name) }}" required></div>
<div class="mb-3"><label class="form-label">Address</label><input type="text" name="address" class="form-control" value="{{ old('address',$branch->address) }}"></div>
<div class="row">
    <div class="col-md-4 mb-3"><label class="form-label">City</label><input type="text" name="city" class="form-control" value="{{ old('city',$branch->city) }}"></div>
    <div class="col-md-4 mb-3"><label class="form-label">State</label><input type="text" name="state" class="form-control" value="{{ old('state',$branch->state) }}"></div>
    <div class="col-md-4 mb-3"><label class="form-label">Pincode</label><input type="text" name="pincode" class="form-control" value="{{ old('pincode',$branch->pincode) }}"></div>
</div>
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone',$branch->phone) }}"></div>
    <div class="col-md-6 mb-3"><label class="form-label">Manager</label><input type="text" name="manager_name" class="form-control" value="{{ old('manager_name',$branch->manager_name) }}"></div>
</div>
<div class="mb-3 form-check"><input type="checkbox" class="form-check-input" name="is_active" value="1" id="isActive" {{ $branch->is_active?'checked':'' }}><label class="form-check-label" for="isActive">Branch is Active</label></div>
<div class="d-flex gap-2 justify-content-end"><a href="{{ route('branches.index') }}" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary">Update Branch</button></div>
</form>
</div></div></div></div>
@endsection
