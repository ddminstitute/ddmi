@extends('website.layout')
@section('title','Server Error')
@section('content')
<div style="min-height:70vh;display:flex;align-items:center;justify-content:center;padding:8rem 0 4rem">
    <div class="text-center">
        <div style="font-size:8rem;font-weight:900;color:#e2e8f0;line-height:1">500</div>
        <h2 class="fw-800 mb-3">Something Went Wrong</h2>
        <p class="text-muted mb-4">We're experiencing a technical issue. Please try again in a moment.</p>
        <a href="{{ route('home') }}" class="btn btn-primary px-4"><i class="bi bi-house me-2"></i>Go Home</a>
    </div>
</div>
@endsection
