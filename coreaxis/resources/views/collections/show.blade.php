@extends('layouts.banking')
@section('title','Collection Plan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold"><i class="bi bi-collection me-2 text-primary"></i>{{ $collectionPlan->plan_number }}</h5>
    <div class="d-flex gap-2">
        <a href="{{ route('collection-plans.edit',$collectionPlan) }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-pencil me-1"></i>Edit</a>
        <a href="{{ route('collection-plans.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
    </div>
</div>
<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><i class="bi bi-info-circle me-2"></i>Plan Details</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr><td class="text-muted small">Plan No</td><td class="fw-semibold small">{{ $collectionPlan->plan_number }}</td></tr>
                    <tr><td class="text-muted small">Customer</td><td class="fw-semibold small">{{ $collectionPlan->customer->name }}<br><span class="text-muted" style="font-size:.72rem">{{ $collectionPlan->customer->customer_id }}</span></td></tr>
                    <tr><td class="text-muted small">Plan Name</td><td class="small">{{ $collectionPlan->plan_name }}</td></tr>
                    <tr><td class="text-muted small">Type</td><td><span class="badge bg-{{ $collectionPlan->getTypeBadge() }}">{{ ucfirst($collectionPlan->collection_type) }}</span></td></tr>
                    <tr><td class="text-muted small">Amount</td><td class="fw-bold text-primary">₹{{ number_format($collectionPlan->collection_amount,2) }}</td></tr>
                    <tr><td class="text-muted small">Total Inst.</td><td class="small">{{ $collectionPlan->total_installments ?? '—' }}</td></tr>
                    <tr><td class="text-muted small">Paid</td><td class="fw-semibold text-success small">{{ $collectionPlan->paidInstallments() }}</td></tr>
                    <tr><td class="text-muted small">Collected</td><td class="fw-bold text-success">₹{{ number_format($collectionPlan->totalCollected(),2) }}</td></tr>
                    <tr><td class="text-muted small">Maturity</td><td class="small">{{ $collectionPlan->maturity_amount ? '₹'.number_format($collectionPlan->maturity_amount,2) : '—' }}</td></tr>
                    <tr><td class="text-muted small">Start Date</td><td class="small">{{ $collectionPlan->start_date->format('d M Y') }}</td></tr>
                    <tr><td class="text-muted small">Status</td><td><span class="badge bg-{{ $collectionPlan->status==='active'?'success':'secondary' }}">{{ ucfirst($collectionPlan->status) }}</span></td></tr>
                </table>
            </div>
        </div>
        <!-- Add Entry -->
        @if($collectionPlan->status === 'active')
        <div class="card mt-3">
            <div class="card-header"><i class="bi bi-plus-circle me-2 text-success"></i>Add Collection Entry</div>
            <div class="card-body">
                <form method="POST" action="{{ route('collection-plans.add-entry', $collectionPlan) }}">
                    @csrf
                    <div class="mb-2">
                        <label class="small">Amount (₹)</label>
                        <input type="number" name="amount" value="{{ $collectionPlan->collection_amount }}" class="form-control form-control-sm" required step="0.01" min="1">
                    </div>
                    <div class="mb-2">
                        <label class="small">Date</label>
                        <input type="date" name="collection_date" value="{{ date('Y-m-d') }}" class="form-control form-control-sm" required>
                    </div>
                    <div class="mb-2">
                        <label class="small">Payment Mode</label>
                        <select name="payment_mode" class="form-select form-select-sm">
                            <option value="cash">Cash</option>
                            <option value="upi">UPI</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cheque">Cheque</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="small">Collected By</label>
                        <input type="text" name="collected_by" value="{{ auth()->user()->name }}" class="form-control form-control-sm">
                    </div>
                    <div class="mb-3">
                        <label class="small">Notes</label>
                        <input type="text" name="notes" class="form-control form-control-sm" placeholder="Optional">
                    </div>
                    <button type="submit" class="btn btn-success btn-sm w-100"><i class="bi bi-plus-circle me-1"></i>Add Entry</button>
                </form>
            </div>
        </div>
        @endif
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-list-ul me-2"></i>Collection History ({{ $collectionPlan->entries->count() }} entries)</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light"><tr><th>#</th><th>Receipt No</th><th>Date</th><th>Amount</th><th>Mode</th><th>Collected By</th><th>Print</th></tr></thead>
                        <tbody>
                            @forelse($collectionPlan->entries->sortByDesc('installment_number') as $entry)
                            <tr>
                                <td class="small">{{ $entry->installment_number }}</td>
                                <td class="fw-semibold small">{{ $entry->receipt_number }}</td>
                                <td class="small">{{ $entry->collection_date->format('d M Y') }}</td>
                                <td class="fw-semibold text-success">₹{{ number_format($entry->amount,2) }}</td>
                                <td><span class="badge bg-light text-dark border small">{{ ucfirst(str_replace('_',' ',$entry->payment_mode)) }}</span></td>
                                <td class="small">{{ $entry->collected_by ?? '—' }}</td>
                                <td><a href="{{ route('print.collection.receipt',$entry) }}" target="_blank" class="btn btn-xs btn-outline-secondary" style="font-size:.72rem;padding:.2rem .5rem"><i class="bi bi-printer"></i></a></td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">No entries yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
