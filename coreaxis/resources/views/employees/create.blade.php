@extends('layouts.banking')
@section('title','Add Employee')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-person-plus me-2 text-primary"></i>Add Employee</h5>
    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>
<form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">
@csrf
<div class="row g-3">
    <div class="col-md-8">
        <div class="card"><div class="card-header"><i class="bi bi-person me-2 text-primary"></i>Personal & Job Details</div>
        <div class="card-body"><div class="row g-3">
            <div class="col-md-6"><label>Full Name *</label><input type="text" name="name" value="{{ old('name') }}" class="form-control" required></div>
            <div class="col-md-6"><label>Phone *</label><input type="text" name="phone" value="{{ old('phone') }}" class="form-control" required></div>
            <div class="col-md-6"><label>Email</label><input type="email" name="email" value="{{ old('email') }}" class="form-control"></div>
            <div class="col-md-6"><label>Joining Date *</label><input type="date" name="joining_date" value="{{ old('joining_date',date('Y-m-d')) }}" class="form-control" required></div>
            <div class="col-md-6"><label>Designation *</label><input type="text" name="designation" value="{{ old('designation') }}" class="form-control" required placeholder="e.g. Branch Manager"></div>
            <div class="col-md-6"><label>Department *</label><input type="text" name="department" value="{{ old('department') }}" class="form-control" required placeholder="e.g. Operations"></div>
        </div></div></div>
    </div>
    <div class="col-md-4">
        <div class="card"><div class="card-header"><i class="bi bi-image me-2 text-primary"></i>Photo</div>
        <div class="card-body text-center">
            <img id="empPhotoPreview" src="#" class="rounded mb-2" style="max-height:120px;display:none">
            <input type="file" name="photo" class="form-control" accept="image/*" onchange="previewImg(this,'empPhotoPreview')">
        </div></div>
    </div>
    <div class="col-12">
        <div class="card"><div class="card-header"><i class="bi bi-currency-rupee me-2 text-primary"></i>Salary Details</div>
        <div class="card-body"><div class="row g-3">
            <div class="col-md-3"><label>Basic Salary (₹) *</label><input type="number" name="basic_salary" value="{{ old('basic_salary') }}" class="form-control" required step="0.01" min="0"></div>
            <div class="col-md-3"><label>HRA (₹)</label><input type="number" name="hra" value="{{ old('hra',0) }}" class="form-control" step="0.01" min="0"></div>
            <div class="col-md-3"><label>Other Allowance (₹)</label><input type="number" name="other_allowance" value="{{ old('other_allowance',0) }}" class="form-control" step="0.01" min="0"></div>
        </div></div>
    </div></div>
    <div class="col-12">
        <div class="card"><div class="card-header"><i class="bi bi-bank me-2 text-primary"></i>Bank & Identity</div>
        <div class="card-body"><div class="row g-3">
            <div class="col-md-3"><label>PAN Number</label><input type="text" name="pan_number" value="{{ old('pan_number') }}" class="form-control" style="text-transform:uppercase"></div>
            <div class="col-md-3"><label>Aadhaar Number</label><input type="text" name="aadhaar_number" value="{{ old('aadhaar_number') }}" class="form-control"></div>
            <div class="col-md-3"><label>Bank Account</label><input type="text" name="bank_account" value="{{ old('bank_account') }}" class="form-control"></div>
            <div class="col-md-3"><label>Bank Name</label><input type="text" name="bank_name" value="{{ old('bank_name') }}" class="form-control"></div>
            <div class="col-md-3"><label>IFSC Code</label><input type="text" name="ifsc_code" value="{{ old('ifsc_code') }}" class="form-control" style="text-transform:uppercase"></div>
        </div></div>
    </div></div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check2 me-2"></i>Add Employee</button>
        <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
    </div>
</div>
</form>
@endsection
@push('scripts')
<script>
function previewImg(input, id) {
    const el = document.getElementById(id);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { el.src = e.target.result; el.style.display = 'block'; };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
