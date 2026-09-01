@extends('layouts.banking')
@section('title','Reports')
@section('content')
<h5 class="fw-bold mb-4">Reports & Analytics</h5>
<div class="row g-3">
    <div class="col-md-4">
        <a href="{{ route('reports.transactions') }}" class="text-decoration-none">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="fs-1 text-primary mb-3"><i class="bi bi-arrow-left-right"></i></div>
                    <h6 class="fw-bold">Transaction Report</h6>
                    <p class="text-muted small mb-0">View all transactions with date range filter, type filter, and totals.</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('reports.statement') }}" class="text-decoration-none">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="fs-1 text-success mb-3"><i class="bi bi-file-earmark-text"></i></div>
                    <h6 class="fw-bold">Account Statement</h6>
                    <p class="text-muted small mb-0">Generate detailed account statement for any account and date range.</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('reports.loans') }}" class="text-decoration-none">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="fs-1 text-warning mb-3"><i class="bi bi-credit-card"></i></div>
                    <h6 class="fw-bold">Loan Portfolio Report</h6>
                    <p class="text-muted small mb-0">Overview of all loans, disbursements, collections, and outstanding amounts.</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
