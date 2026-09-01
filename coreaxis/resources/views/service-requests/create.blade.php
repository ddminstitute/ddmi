@extends('layouts.banking')
@section('title','New Service Request')
@section('content')
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('service-requests.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">New Service Request</h5>
</div>
<div class="row justify-content-center"><div class="col-lg-6"><div class="card"><div class="card-body">
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
<form method="POST" action="{{ route('service-requests.store') }}">@csrf
<div class="mb-3"><label class="form-label">Customer</label><select name="customer_id" class="form-select"><option value="">— Walk-in / Not specified —</option>@foreach($customers as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach</select></div>
<div class="mb-3"><label class="form-label">Account</label><select name="account_id" class="form-select"><option value="">— Not account-specific —</option>@foreach($accounts as $a)<option value="{{ $a->id }}">{{ $a->account_number }} — {{ $a->customer?->name??$a->user?->name }}</option>@endforeach</select></div>
<div class="mb-3"><label class="form-label">Request Type <span class="text-danger">*</span></label>
<select name="request_type" class="form-select" required>
@php $types=['stop_cheque'=>'Stop Cheque Payment','address_change'=>'Address Change','mobile_change'=>'Mobile Number Change','email_change'=>'Email Change','passbook_reissue'=>'Passbook Reissue','account_unfreeze'=>'Account Unfreeze','statement_request'=>'Statement Request','nominee_change'=>'Nominee Change','other'=>'Other']; @endphp
@foreach($types as $v=>$l)<option value="{{ $v }}">{{ $l }}</option>@endforeach
</select></div>
<div class="mb-3"><label class="form-label">Details</label><textarea name="details" class="form-control" rows="3" placeholder="Provide any relevant details..."></textarea></div>
<div class="d-flex gap-2 justify-content-end"><a href="{{ route('service-requests.index') }}" class="btn btn-outline-secondary">Cancel</a><button type="submit" class="btn btn-primary">Submit Request</button></div>
</form>
</div></div></div></div>
@endsection
