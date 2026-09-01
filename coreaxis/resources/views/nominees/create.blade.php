@extends('layouts.banking')
@section('title','Add Nominee')
@section('content')
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('accounts.nominees.index',$account) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Add Nominee — {{ $account->account_number }}</h5>
</div>
<div class="row justify-content-center"><div class="col-lg-6"><div class="card"><div class="card-body">
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<form method="POST" action="{{ route('accounts.nominees.store',$account) }}">@csrf
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Full Name <span class="text-danger">*</span></label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required></div>
    <div class="col-md-6 mb-3"><label class="form-label">Relation <span class="text-danger">*</span></label>
    <select name="relation" class="form-select" required>
        <option value="">— Select —</option>
        @foreach(['Spouse','Son','Daughter','Father','Mother','Brother','Sister','Grandson','Granddaughter','Other'] as $r)
        <option value="{{ $r }}" {{ old('relation') === $r ? 'selected' : '' }}>{{ $r }}</option>
        @endforeach
    </select></div>
</div>
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Date of Birth</label><input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}"></div>
    <div class="col-md-6 mb-3"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone') }}"></div>
</div>
<div class="mb-3"><label class="form-label">Address</label><input type="text" name="address" class="form-control" value="{{ old('address') }}"></div>
<div class="row">
    <div class="col-md-6 mb-3"><label class="form-label">Share (%) <span class="text-danger">*</span></label><input type="number" name="share_percent" class="form-control" min="1" max="100" value="{{ old('share_percent',100) }}" required></div>
    <div class="col-md-6 mb-3 d-flex align-items-end"><div class="form-check"><input class="form-check-input" type="checkbox" name="is_minor" value="1" id="isMinor"><label class="form-check-label" for="isMinor">Nominee is a Minor</label></div></div>
</div>
<div class="mb-3" id="guardianDiv" style="display:none"><label class="form-label">Guardian Name (required if minor)</label><input type="text" name="guardian_name" class="form-control" value="{{ old('guardian_name') }}"></div>
<div class="d-flex gap-2 justify-content-end">
    <a href="{{ route('accounts.nominees.index',$account) }}" class="btn btn-outline-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">Add Nominee</button>
</div>
</form>
</div></div></div></div>
@push('scripts')
<script>
document.getElementById('isMinor').addEventListener('change', function() {
    document.getElementById('guardianDiv').style.display = this.checked ? 'block' : 'none';
});
</script>
@endpush
@endsection
