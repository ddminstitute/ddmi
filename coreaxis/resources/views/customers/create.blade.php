@extends('layouts.banking')
@section('title','Add Customer')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-person-plus me-2 text-primary"></i>Add New Customer</h5>
    <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>
<form method="POST" action="{{ route('customers.store') }}" enctype="multipart/form-data">
@csrf
<div class="row g-3">
    <!-- Personal Info -->
    <div class="col-12">
        <div class="card">
            <div class="card-header"><i class="bi bi-person me-2 text-primary"></i>Personal Information</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label>Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" required placeholder="Enter full name">
                    </div>
                    <div class="col-md-4">
                        <label>Father's Name</label>
                        <input type="text" name="father_name" value="{{ old('father_name') }}" class="form-control" placeholder="Father's name">
                    </div>
                    <div class="col-md-4">
                        <label>Mother's Name</label>
                        <input type="text" name="mother_name" value="{{ old('mother_name') }}" class="form-control" placeholder="Mother's name">
                    </div>
                    <div class="col-md-4">
                        <label>Gender <span class="text-danger">*</span></label>
                        <select name="gender" class="form-select" required>
                            <option value="male" {{ old('gender')=='male'?'selected':'' }}>Male</option>
                            <option value="female" {{ old('gender')=='female'?'selected':'' }}>Female</option>
                            <option value="other" {{ old('gender')=='other'?'selected':'' }}>Other</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Date of Birth <span class="text-danger">*</span></label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label>Occupation</label>
                        <input type="text" name="occupation" value="{{ old('occupation') }}" class="form-control" placeholder="e.g. Farmer, Teacher">
                    </div>
                    <div class="col-md-4">
                        <label>Annual Income (₹)</label>
                        <input type="number" name="annual_income" value="{{ old('annual_income') }}" class="form-control" placeholder="0.00" step="0.01" min="0">
                    </div>
                    <div class="col-md-4">
                        <label>PAN Number</label>
                        <input type="text" name="pan_number" value="{{ old('pan_number') }}" class="form-control" placeholder="ABCDE1234F" maxlength="10" style="text-transform:uppercase">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact -->
    <div class="col-12">
        <div class="card">
            <div class="card-header"><i class="bi bi-telephone me-2 text-primary"></i>Contact Details</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label>Phone <span class="text-danger">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" required placeholder="+91 XXXXXXXXXX">
                    </div>
                    <div class="col-md-4">
                        <label>Alternate Phone</label>
                        <input type="text" name="alternate_phone" value="{{ old('alternate_phone') }}" class="form-control" placeholder="+91 XXXXXXXXXX">
                    </div>
                    <div class="col-md-4">
                        <label>Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="email@example.com">
                    </div>
                    <div class="col-md-8">
                        <label>Address <span class="text-danger">*</span></label>
                        <textarea name="address" class="form-control" rows="2" required placeholder="Street address, village/town">{{ old('address') }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label>City <span class="text-danger">*</span></label>
                        <input type="text" name="city" value="{{ old('city') }}" class="form-control" required placeholder="City">
                    </div>
                    <div class="col-md-4">
                        <label>State <span class="text-danger">*</span></label>
                        <input type="text" name="state" value="{{ old('state','Bihar') }}" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label>Pincode <span class="text-danger">*</span></label>
                        <input type="text" name="pincode" value="{{ old('pincode') }}" class="form-control" required placeholder="848101">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Identity -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-card-text me-2 text-primary"></i>Identity Documents</div>
            <div class="card-body">
                <div class="mb-3">
                    <label>PAN Number</label>
                    <input type="text" name="pan_number" value="{{ old('pan_number') }}" class="form-control" placeholder="ABCDE1234F" maxlength="10" style="text-transform:uppercase">
                </div>
                <div class="mb-3">
                    <label>Aadhaar Number</label>
                    <input type="text" name="aadhaar_number" value="{{ old('aadhaar_number') }}" class="form-control" placeholder="XXXX XXXX XXXX" maxlength="14">
                </div>
                <div class="mb-3">
                    <label>Notes</label>
                    <textarea name="notes" class="form-control" rows="2" placeholder="Additional notes...">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>
    </div>
    <!-- Documents Upload -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-cloud-upload me-2 text-primary"></i>Document Upload</div>
            <div class="card-body">
                <div class="mb-3">
                    <label>Customer Photo</label>
                    <input type="file" name="photo" class="form-control" accept="image/*" onchange="previewImg(this,'prevPhoto')">
                    <img id="prevPhoto" src="#" class="mt-2 rounded" style="max-height:80px;display:none">
                </div>
                <div class="mb-3">
                    <label>Signature</label>
                    <input type="file" name="signature" class="form-control" accept="image/*" onchange="previewImg(this,'prevSig')">
                    <img id="prevSig" src="#" class="mt-2" style="max-height:60px;display:none">
                </div>
                <div class="mb-3">
                    <label>PAN Card Document</label>
                    <input type="file" name="pan_document" class="form-control" accept="image/*,application/pdf">
                </div>
                <div class="mb-0">
                    <label>Aadhaar Document</label>
                    <input type="file" name="aadhaar_document" class="form-control" accept="image/*,application/pdf">
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check2 me-2"></i>Create Customer</button>
        <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary ms-2">Cancel</a>
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
