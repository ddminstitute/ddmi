@extends('layouts.banking')
@section('title','Edit Account')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-6">
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('accounts.show',$account) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Edit Account {{ $account->account_number }}</h5>
</div>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('accounts.update',$account) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="active" {{ $account->status=='active'?'selected':'' }}>Active</option>
                    <option value="inactive" {{ $account->status=='inactive'?'selected':'' }}>Inactive</option>
                    <option value="frozen" {{ $account->status=='frozen'?'selected':'' }}>Frozen</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Notes</label>
                <textarea name="notes" class="form-control" rows="3">{{ $account->notes }}</textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('accounts.show',$account) }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
