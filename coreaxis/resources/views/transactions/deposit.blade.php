@extends('layouts.banking')
@section('title','Deposit')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-6">
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Cash Deposit</h5>
</div>
<div class="card border-success">
    <div class="card-header bg-success text-white"><i class="bi bi-plus-circle me-2"></i>Deposit Funds</div>
    <div class="card-body">
        <form method="POST" action="{{ route('transactions.deposit.post') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Select Account <span class="text-danger">*</span></label>
                <select name="account_id" id="accountSelect" class="form-select @error('account_id') is-invalid @enderror" required onchange="updateBalance(this)">
                    <option value="">Choose account...</option>
                    @foreach($accounts as $acc)
                    <option value="{{ $acc->id }}" data-balance="{{ $acc->balance }}" {{ request('account_id')==$acc->id||old('account_id')==$acc->id?'selected':'' }}>
                        {{ $acc->account_number }} — {{ $acc->user->name }} ({{ $acc->getTypeLabel() }})
                    </option>
                    @endforeach
                </select>
                @error('account_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div id="balanceInfo" class="form-text text-muted"></div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Amount ($) <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" min="1" step="0.01" placeholder="0.00" required>
                    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Description</label>
                <input type="text" name="description" class="form-control" value="{{ old('description') }}" placeholder="Cash deposit">
            </div>
            <button type="submit" class="btn btn-success w-100 py-2 fw-semibold"><i class="bi bi-check-circle me-2"></i>Process Deposit</button>
        </form>
    </div>
</div>
</div>
</div>
@push('scripts')
<script>
function updateBalance(sel){
    const opt = sel.options[sel.selectedIndex];
    const bal = opt.dataset.balance;
    document.getElementById('balanceInfo').textContent = bal ? 'Current Balance: $' + parseFloat(bal).toFixed(2) : '';
}
document.addEventListener('DOMContentLoaded',()=>{ const s=document.getElementById('accountSelect'); if(s.value) updateBalance(s); });
</script>
@endpush
@endsection
