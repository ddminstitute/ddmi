@extends('layouts.banking')
@section('title','Edit Employee')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-pencil me-2 text-primary"></i>Edit Employee — {{ $employee->name }}</h5>
    <a href="{{ route('employees.show',$employee) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>
<form method="POST" action="{{ route('employees.update',$employee) }}" enctype="multipart/form-data">
@csrf @method('PUT')
<div class="row g-3">
    <div class="col-12"><div class="card"><div class="card-header"><i class="bi bi-person me-2 text-primary"></i>Personal & Job Details</div>
    <div class="card-body"><div class="row g-3">
        <div class="col-md-4"><label>Full Name *</label><input type="text" name="name" value="{{ old('name',$employee->name) }}" class="form-control" required></div>
        <div class="col-md-4"><label>Phone *</label><input type="text" name="phone" value="{{ old('phone',$employee->phone) }}" class="form-control" required></div>
        <div class="col-md-4"><label>Email</label><input type="email" name="email" value="{{ old('email',$employee->email) }}" class="form-control"></div>
        <div class="col-md-4"><label>Designation *</label><input type="text" name="designation" value="{{ old('designation',$employee->designation) }}" class="form-control" required></div>
        <div class="col-md-4"><label>Department *</label><input type="text" name="department" value="{{ old('department',$employee->department) }}" class="form-control" required></div>
        <div class="col-md-4"><label>Joining Date *</label><input type="date" name="joining_date" value="{{ old('joining_date',$employee->joining_date?->format('Y-m-d')) }}" class="form-control" required></div>
        <div class="col-md-3"><label>Basic Salary (₹) *</label><input type="number" name="basic_salary" value="{{ old('basic_salary',$employee->basic_salary) }}" class="form-control" required step="0.01"></div>
        <div class="col-md-3"><label>HRA (₹)</label><input type="number" name="hra" value="{{ old('hra',$employee->hra) }}" class="form-control" step="0.01"></div>
        <div class="col-md-3"><label>Other Allowance (₹)</label><input type="number" name="other_allowance" value="{{ old('other_allowance',$employee->other_allowance) }}" class="form-control" step="0.01"></div>
        <div class="col-md-3"><label>Status</label>
            <select name="status" class="form-select">
                <option value="active" {{ old('status',$employee->status)=='active'?'selected':'' }}>Active</option>
                <option value="inactive" {{ old('status',$employee->status)=='inactive'?'selected':'' }}>Inactive</option>
            </select>
        </div>
        <div class="col-md-3"><label>PAN</label><input type="text" name="pan_number" value="{{ old('pan_number',$employee->pan_number) }}" class="form-control" style="text-transform:uppercase"></div>
        <div class="col-md-3"><label>Aadhaar</label><input type="text" name="aadhaar_number" value="{{ old('aadhaar_number',$employee->aadhaar_number) }}" class="form-control"></div>
        <div class="col-md-3"><label>Bank Account</label><input type="text" name="bank_account" value="{{ old('bank_account',$employee->bank_account) }}" class="form-control"></div>
        <div class="col-md-3"><label>Bank Name</label><input type="text" name="bank_name" value="{{ old('bank_name',$employee->bank_name) }}" class="form-control"></div>
        <div class="col-md-3"><label>IFSC Code</label><input type="text" name="ifsc_code" value="{{ old('ifsc_code',$employee->ifsc_code) }}" class="form-control" style="text-transform:uppercase"></div>
        <div class="col-md-3">
            <label>Photo</label>
            @if($employee->photo)<img src="{{ Storage::url($employee->photo) }}" class="d-block mb-1 rounded" style="max-height:50px">@endif
            <input type="file" name="photo" class="form-control" accept="image/*">
        </div>
    </div></div></div></div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check2 me-2"></i>Update Employee</button>
        <a href="{{ route('employees.show',$employee) }}" class="btn btn-outline-secondary ms-2">Cancel</a>
    </div>
</div>
</form>
@endsection
