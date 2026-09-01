@extends('layouts.banking')
@section('title','Transfer')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-6">
<div class="d-flex align-items-center mb-3 gap-2">
    <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 fw-bold">Fund Transfer</h5>
</div>
<div class="card border-primary">
    <div class="card-header bg-primary text-white"><i class="bi bi-send me-2"></i>Transfer Funds</div>
    <div class="card-body">
        <form method="POST" action="{{ route('transactions.transfer.post') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">From Account <span class="text-danger">*</span></label>
                <select name="from_account_id" class="form-select @error('from_account_id') is-invalid @enderror" required onchange="updateBalance(this)">
                    <option value="">Source account...</option>
                    @foreach($accounts as $acc)
                    <option value="{{ $acc->id }}" data-balance="{{ $acc->balance }}" {{ old('from_account_id')==$acc->id?'selected':'' }}>
                        {{ $acc->account_number }} — {{ $acc->user->name }} (₹{{ number_format($acc->balance,2) }})
                    </option>
                    @endforeach
                </select>
                @error('from_account_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div id="balanceInfo" class="form-text text-muted"></div>
            </div>
            <div class="text-center my-2 text-muted"><i class="bi bi-arrow-down fs-4"></i></div>
            <div class="mb-3">
                <label class="form-label fw-semibold">To Account <span class="text-danger">*</span></label>
                <select name="to_account_id" class="form-select @error('to_account_id') is-invalid @enderror" required>
                    <option value="">Destination account...</option>
                    @foreach($accounts as $acc)
                    <option value="{{ $acc->id }}" {{ old('to_account_id')==$acc->id?'selected':'' }}>
                        {{ $acc->account_number }} — {{ $acc->user->name }}
                    </option>
                    @endforeach
                </select>
                @error('to_account_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Amount (₹) <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">₹</span>
                    <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" min="1" step="0.01" placeholder="0.00" required>
                    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Description</label>
                <input type="text" name="description" class="form-control" value="{{ old('description') }}" placeholder="Fund transfer">
            </div>
            @if(session('otp_required'))
            <div class="mb-3">
                <label class="form-label fw-semibold text-warning"><i class="bi bi-shield-lock me-1"></i>OTP Verification Required</label>
                <input type="text" name="otp" class="form-control @error('otp') is-invalid @enderror" placeholder="Enter 6-digit OTP sent to your phone" maxlength="6" inputmode="numeric" pattern="[0-9]{6}">
                @error('otp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="form-text">An OTP has been sent to your registered mobile number for this high-value transaction.</div>
            </div>
            @endif
            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold"><i class="bi bi-send me-2"></i>Transfer Now</button>
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
    document.getElementById('balanceInfo').textContent = bal ? 'Available: ₹' + parseFloat(bal).toFixed(2) : '';
}
</script>
@endpush
@endsection
