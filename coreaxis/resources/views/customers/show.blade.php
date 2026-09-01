@extends('layouts.banking')
@section('title','Customer Profile')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-person-circle me-2 text-primary"></i>Customer Profile</h5>
    <div class="d-flex gap-2">
        <a href="{{ route('customers.edit',$customer) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>
        @if(!$customer->portalUser)
        <form method="POST" action="{{ route('customers.portal-account',$customer) }}" class="d-inline" onsubmit="return confirm('Create portal login for this customer?')">
            @csrf
            <button class="btn btn-outline-success btn-sm"><i class="bi bi-person-badge me-1"></i>Create Portal Account</button>
        </form>
        @else
        <span class="btn btn-sm btn-success disabled"><i class="bi bi-check-circle me-1"></i>Portal Active</span>
        @endif
        <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
</div>
<div class="row g-3">
    <!-- Profile Card -->
    <div class="col-md-4">
        <div class="card text-center p-3">
            @if($customer->photo)
                <img src="{{ Storage::url($customer->photo) }}" class="rounded-circle mx-auto mb-3" width="100" height="100" style="object-fit:cover;border:4px solid #e2e8f0">
            @else
                <div class="rounded-circle bg-primary mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:100px;height:100px">
                    <span class="text-white fw-bold" style="font-size:2rem">{{ strtoupper(substr($customer->name,0,1)) }}</span>
                </div>
            @endif
            <h6 class="fw-bold mb-1">{{ $customer->name }}</h6>
            <div class="mb-2"><span class="badge bg-secondary">{{ $customer->customer_id }}</span></div>
            <span class="badge bg-{{ $customer->getStatusBadge() }} px-3">{{ ucfirst($customer->status) }}</span>
            @if($customer->signature)
            <div class="mt-3 border-top pt-3">
                <div class="text-muted small mb-1">Signature</div>
                <img src="{{ Storage::url($customer->signature) }}" class="img-fluid" style="max-height:50px">
            </div>
            @endif
        </div>
        <!-- Documents -->
        <div class="card mt-3">
            <div class="card-header"><i class="bi bi-file-earmark me-2"></i>Documents</div>
            <div class="card-body">
                @if($customer->pan_document)
                <div class="mb-2"><a href="{{ Storage::url($customer->pan_document) }}" target="_blank" class="btn btn-outline-primary btn-sm w-100"><i class="bi bi-file-earmark-text me-1"></i>View PAN Document</a></div>
                @else <div class="text-muted small mb-2"><i class="bi bi-x-circle me-1 text-danger"></i>PAN Document not uploaded</div> @endif
                @if($customer->aadhaar_document)
                <div><a href="{{ Storage::url($customer->aadhaar_document) }}" target="_blank" class="btn btn-outline-primary btn-sm w-100"><i class="bi bi-file-earmark-text me-1"></i>View Aadhaar Document</a></div>
                @else <div class="text-muted small"><i class="bi bi-x-circle me-1 text-danger"></i>Aadhaar not uploaded</div> @endif
            </div>
        </div>
    </div>
    <!-- Details -->
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-info-circle me-2"></i>Personal Details</div>
            <div class="card-body">
                <div class="row g-2">
                    @foreach([['Father','father_name'],['Mother','mother_name'],['Gender','gender'],['DOB','date_of_birth'],['Phone','phone'],['Alt Phone','alternate_phone'],['Email','email'],['Occupation','occupation'],['Annual Income','annual_income'],['PAN','pan_number'],['Aadhaar','aadhaar_number']] as [$label,$field])
                    <div class="col-md-6">
                        <div class="d-flex gap-2">
                            <span class="text-muted small" style="min-width:100px">{{ $label }}:</span>
                            <span class="small fw-semibold">
                                @if($field === 'annual_income' && $customer->$field) ₹{{ number_format($customer->$field,2) }}
                                @elseif($field === 'date_of_birth' && $customer->$field) {{ $customer->date_of_birth->format('d M Y') }}
                                @else {{ $customer->$field ?: '—' }}
                                @endif
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($customer->address)
                <div class="mt-2 pt-2 border-top">
                    <span class="text-muted small">Address:</span>
                    <span class="small fw-semibold ms-2">{{ $customer->address }}, {{ $customer->city }}, {{ $customer->state }} — {{ $customer->pincode }}</span>
                </div>
                @endif
                @if($customer->notes)
                <div class="mt-2 pt-2 border-top">
                    <span class="text-muted small">Notes:</span>
                    <span class="small ms-2">{{ $customer->notes }}</span>
                </div>
                @endif
            </div>
        </div>
        <!-- Linked Accounts -->
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-wallet2 me-2"></i>Linked Accounts</span>
                <a href="{{ route('accounts.create') }}?customer_id={{ $customer->id }}" class="btn btn-sm btn-outline-primary">Open Account</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Account No</th><th>Type</th><th>Balance</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                        @forelse($customer->accounts as $acc)
                        <tr>
                            <td class="fw-semibold small">{{ $acc->account_number }}</td>
                            <td>{{ $acc->getTypeLabel() }}</td>
                            <td class="text-success fw-semibold">₹{{ number_format($acc->balance,2) }}</td>
                            <td><span class="badge bg-{{ $acc->status==='active'?'success':'secondary' }}">{{ ucfirst($acc->status) }}</span></td>
                            <td><a href="{{ route('accounts.show',$acc) }}" class="btn btn-xs btn-outline-primary" style="font-size:.75rem;padding:.2rem .55rem"><i class="bi bi-eye"></i></a></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3 small">No accounts linked</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Linked Loans -->
        <div class="card">
            <div class="card-header"><i class="bi bi-credit-card me-2"></i>Loan History</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Loan No</th><th>Type</th><th>Amount</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                        @forelse($customer->loans as $loan)
                        <tr>
                            <td class="fw-semibold small">{{ $loan->loan_number }}</td>
                            <td class="small">{{ ucwords(str_replace('_',' ',$loan->loan_type)) }}</td>
                            <td class="fw-semibold">₹{{ number_format($loan->principal_amount,2) }}</td>
                            <td><span class="badge bg-{{ $loan->getStatusBadge() }}">{{ ucfirst($loan->status) }}</span></td>
                            <td><a href="{{ route('loans.show',$loan) }}" class="btn btn-xs btn-outline-primary" style="font-size:.75rem;padding:.2rem .55rem"><i class="bi bi-eye"></i></a></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3 small">No loans found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
