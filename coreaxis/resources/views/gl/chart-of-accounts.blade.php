@extends('layouts.banking')
@section('title','Chart of Accounts')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-journal-text me-2 text-primary"></i>Chart of Accounts</h5>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="bi bi-plus me-1"></i>Add Account
    </button>
</div>

@foreach(['asset'=>'Assets','liability'=>'Liabilities','equity'=>'Equity','income'=>'Income','expense'=>'Expenses'] as $type => $label)
@if(isset($accounts[$type]) && $accounts[$type]->count())
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><span class="badge bg-{{ match($type){'asset'=>'primary','liability'=>'danger','equity'=>'info','income'=>'success','expense'=>'warning',default=>'secondary'} }} me-2">{{ $label }}</span></span>
        <small class="text-muted">{{ $accounts[$type]->count() }} accounts</small>
    </div>
    <div class="card-body p-0">
        <table class="table table-sm mb-0">
            <thead class="table-light">
                <tr><th>Code</th><th>Name</th><th>Normal Balance</th><th class="text-end">Current Balance</th><th></th></tr>
            </thead>
            <tbody>
                @foreach($accounts[$type]->sortBy('code') as $acc)
                <tr>
                    <td><code>{{ $acc->code }}</code></td>
                    <td>{{ $acc->name }} @if($acc->is_system)<span class="badge bg-secondary ms-1" style="font-size:.65rem">system</span>@endif</td>
                    <td><span class="badge bg-{{ $acc->normal_balance==='debit'?'primary':'success' }}">{{ ucfirst($acc->normal_balance) }}</span></td>
                    <td class="text-end fw-semibold">₹{{ number_format($acc->getBalance(),2) }}</td>
                    <td><a href="{{ route('gl.ledger', ['account_id'=>$acc->id]) }}" class="btn btn-xs btn-outline-secondary" style="font-size:.72rem;padding:.2rem .5rem">Ledger</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endforeach

<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Add GL Account</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form method="POST" action="{{ route('gl.accounts.store') }}">
            @csrf
            <div class="modal-body row g-3">
                <div class="col-md-4"><label class="form-label">Code <span class="text-danger">*</span></label><input type="text" name="code" class="form-control" placeholder="e.g. 1005" required maxlength="10"></div>
                <div class="col-md-8"><label class="form-label">Account Name <span class="text-danger">*</span></label><input type="text" name="name" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">Type <span class="text-danger">*</span></label><select name="type" class="form-select" required onchange="setNormal(this)"><option value="asset">Asset</option><option value="liability">Liability</option><option value="equity">Equity</option><option value="income">Income</option><option value="expense">Expense</option></select></div>
                <div class="col-md-6"><label class="form-label">Normal Balance <span class="text-danger">*</span></label><select name="normal_balance" id="normalBalance" class="form-select" required><option value="debit">Debit</option><option value="credit">Credit</option></select></div>
                <div class="col-12"><label class="form-label">Description</label><input type="text" name="description" class="form-control"></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Add Account</button></div>
        </form>
    </div></div>
</div>
@endsection
@push('scripts')
<script>function setNormal(sel){const n={asset:'debit',liability:'credit',equity:'credit',income:'credit',expense:'debit'};document.getElementById('normalBalance').value=n[sel.value]||'debit';}</script>
@endpush
