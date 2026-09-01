@extends('website.layout')
@section('title','Page Not Found')
@section('content')
<div style="min-height:70vh;display:flex;align-items:center;justify-content:center;padding:8rem 0 4rem">
    <div class="text-center">
        <div style="font-size:8rem;font-weight:900;color:#e2e8f0;line-height:1">404</div>
        <h2 class="fw-800 mb-3">Page Not Found</h2>
        <p class="text-muted mb-4">The page you're looking for doesn't exist or has been moved.</p>
        <div class="d-flex gap-3 justify-content-center">
            <a href="{{ route('home') }}" class="btn btn-primary px-4"><i class="bi bi-house me-2"></i>Go Home</a>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-primary px-4">Dashboard</a>
        </div>
    </div>
</div>
@endsection
