@extends('website.layout')
@section('title','Contact Us')
@section('content')
<section style="background:linear-gradient(135deg,#0D47A1,#1565C0);padding:8rem 0 5rem">
    <div class="container text-center">
        <div class="section-tag mb-3" style="color:#fff;border-color:rgba(255,255,255,.3);background:rgba(255,255,255,.1)"><i class="bi bi-envelope"></i> Contact Us</div>
        <h1 class="text-white fw-800" style="font-size:clamp(2rem,5vw,3rem)">We're Here to Help</h1>
        <p class="text-white opacity-70 mt-3 mx-auto" style="max-width:500px">Have a question? Need support? Our team is available 24/7 to assist you.</p>
    </div>
</section>

<section style="padding:5rem 0">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4">
                <h4 class="fw-800 mb-4">Get in Touch</h4>
                @foreach([['bi-telephone','Phone Support','+1 (800) COREAXIS','Mon–Fri, 9AM–6PM EST','#1565C0','rgba(21,101,192,.08)'],['bi-envelope','Email Us','support@coreaxis.com','24–48 hour response time','#00897B','rgba(0,137,123,.08)'],['bi-geo-alt','Visit Us','123 Financial District, New York, NY 10004','Mon–Fri, 9AM–5PM','#6A1B9A','rgba(106,27,154,.08)'],['bi-chat-dots','Live Chat','Available in the app','Usually responds in under 2 minutes','#E65100','rgba(230,81,0,.08)']] as $c)
                <div class="d-flex gap-3 mb-4">
                    <div style="width:48px;height:48px;border-radius:12px;background:{{ $c[5] }};display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <i class="bi {{ $c[0] }}" style="color:{{ $c[4] }};font-size:1.2rem"></i>
                    </div>
                    <div>
                        <div class="fw-700 small mb-0">{{ $c[1] }}</div>
                        <div class="fw-semibold">{{ $c[2] }}</div>
                        <div class="text-muted small">{{ $c[3] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm p-4" style="border-radius:20px">
                    <h5 class="fw-700 mb-4">Send us a Message</h5>
                    @if(session('contact_success'))
                    <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>Your message has been sent! We'll respond within 24–48 hours.</div>
                    @endif
                    <form method="POST" action="{{ route('contact.send') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="John Smith">
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="john@example.com">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Phone (optional)</label>
                                <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+1 (555) 000-0000">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                                <select name="subject" class="form-select" required>
                                    <option value="">Select a topic...</option>
                                    <option>Account Opening</option>
                                    <option>Loan Inquiry</option>
                                    <option>Technical Support</option>
                                    <option>Transaction Issue</option>
                                    <option>General Inquiry</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                                <textarea name="message" class="form-control @error('message') is-invalid @enderror" rows="5" required placeholder="Tell us how we can help...">{{ old('message') }}</textarea>
                                @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary px-5 py-2 fw-semibold"><i class="bi bi-send me-2"></i>Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
