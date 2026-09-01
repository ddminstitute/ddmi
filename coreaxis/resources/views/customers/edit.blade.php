@extends('layouts.banking')
@section('title','Edit Customer')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-pencil me-2 text-primary"></i>Edit Customer — {{ $customer->customer_id }}</h5>
    <a href="{{ route('customers.show',$customer) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
</div>
<form method="POST" action="{{ route('customers.update',$customer) }}" enctype="multipart/form-data">
@csrf @method('PUT')
<div class="row g-3">
    <div class="col-12"><div class="card">
        <div class="card-header"><i class="bi bi-person me-2 text-primary"></i>Personal Information</div>
        <div class="card-body"><div class="row g-3">
            <div class="col-md-4"><label>Full Name *</label><input type="text" name="name" value="{{ old('name',$customer->name) }}" class="form-control" required></div>
            <div class="col-md-4"><label>Father's Name</label><input type="text" name="father_name" value="{{ old('father_name',$customer->father_name) }}" class="form-control"></div>
            <div class="col-md-4"><label>Mother's Name</label><input type="text" name="mother_name" value="{{ old('mother_name',$customer->mother_name) }}" class="form-control"></div>
            <div class="col-md-3"><label>Gender *</label>
                <select name="gender" class="form-select" required>
                    <option value="male" {{ old('gender',$customer->gender)=='male'?'selected':'' }}>Male</option>
                    <option value="female" {{ old('gender',$customer->gender)=='female'?'selected':'' }}>Female</option>
                    <option value="other" {{ old('gender',$customer->gender)=='other'?'selected':'' }}>Other</option>
                </select>
            </div>
            <div class="col-md-3"><label>Date of Birth *</label><input type="date" name="date_of_birth" value="{{ old('date_of_birth',$customer->date_of_birth?->format('Y-m-d')) }}" class="form-control" required></div>
            <div class="col-md-3"><label>Occupation</label><input type="text" name="occupation" value="{{ old('occupation',$customer->occupation) }}" class="form-control"></div>
            <div class="col-md-3"><label>Annual Income (₹)</label><input type="number" name="annual_income" value="{{ old('annual_income',$customer->annual_income) }}" class="form-control" step="0.01" min="0"></div>
            <div class="col-md-3"><label>Status</label>
                <select name="status" class="form-select">
                    <option value="active" {{ old('status',$customer->status)=='active'?'selected':'' }}>Active</option>
                    <option value="inactive" {{ old('status',$customer->status)=='inactive'?'selected':'' }}>Inactive</option>
                    <option value="blacklisted" {{ old('status',$customer->status)=='blacklisted'?'selected':'' }}>Blacklisted</option>
                </select>
            </div>
        </div></div>
    </div></div>
    <div class="col-12"><div class="card">
        <div class="card-header"><i class="bi bi-telephone me-2 text-primary"></i>Contact Details</div>
        <div class="card-body"><div class="row g-3">
            <div class="col-md-4"><label>Phone *</label><input type="text" name="phone" value="{{ old('phone',$customer->phone) }}" class="form-control" required></div>
            <div class="col-md-4"><label>Alternate Phone</label><input type="text" name="alternate_phone" value="{{ old('alternate_phone',$customer->alternate_phone) }}" class="form-control"></div>
            <div class="col-md-4"><label>Email</label><input type="email" name="email" value="{{ old('email',$customer->email) }}" class="form-control"></div>
            <div class="col-md-8"><label>Address *</label><textarea name="address" class="form-control" rows="2" required>{{ old('address',$customer->address) }}</textarea></div>
            <div class="col-md-4"><label>City *</label><input type="text" name="city" value="{{ old('city',$customer->city) }}" class="form-control" required></div>
            <div class="col-md-4"><label>State *</label><input type="text" name="state" value="{{ old('state',$customer->state) }}" class="form-control" required></div>
            <div class="col-md-4"><label>Pincode *</label><input type="text" name="pincode" value="{{ old('pincode',$customer->pincode) }}" class="form-control" required></div>
            <div class="col-md-4"><label>PAN Number</label><input type="text" name="pan_number" value="{{ old('pan_number',$customer->pan_number) }}" class="form-control" style="text-transform:uppercase"></div>
            <div class="col-md-4"><label>Aadhaar Number</label><input type="text" name="aadhaar_number" value="{{ old('aadhaar_number',$customer->aadhaar_number) }}" class="form-control"></div>
            <div class="col-md-4"><label>Notes</label><input type="text" name="notes" value="{{ old('notes',$customer->notes) }}" class="form-control"></div>
        </div></div>
    </div></div>
    <div class="col-12"><div class="card">
        <div class="card-header"><i class="bi bi-cloud-upload me-2 text-primary"></i>Update Documents (leave blank to keep existing)</div>
        <div class="card-body"><div class="row g-3">
            <div class="col-md-3">
                <label>Photo</label>
                @if($customer->photo)<img src="{{ Storage::url($customer->photo) }}" class="d-block mb-2 rounded" style="max-height:60px">@endif
                <input type="file" name="photo" class="form-control" accept="image/*">
            </div>
            <div class="col-md-3">
                <label>Signature</label>
                @if($customer->signature)<img src="{{ Storage::url($customer->signature) }}" class="d-block mb-2" style="max-height:40px">@endif
                <input type="file" name="signature" class="form-control" accept="image/*">
            </div>
            <div class="col-md-3">
                <label>PAN Document</label>
                @if($customer->pan_document)<a href="{{ Storage::url($customer->pan_document) }}" target="_blank" class="d-block mb-2 small text-primary">View existing</a>@endif
                <input type="file" name="pan_document" class="form-control" accept="image/*,application/pdf">
            </div>
            <div class="col-md-3">
                <label>Aadhaar Document</label>
                @if($customer->aadhaar_document)<a href="{{ Storage::url($customer->aadhaar_document) }}" target="_blank" class="d-block mb-2 small text-primary">View existing</a>@endif
                <input type="file" name="aadhaar_document" class="form-control" accept="image/*,application/pdf">
            </div>
        </div></div>
    </div></div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check2 me-2"></i>Update Customer</button>
        <a href="{{ route('customers.show',$customer) }}" class="btn btn-outline-secondary ms-2">Cancel</a>
    </div>
</div>
</form>
@endsection
