<footer class="footer-section py-5 mt-0">
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-lg-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="brand-icon"><i class="bi bi-bank2"></i></div>
                    <div>
                        <span class="fw-800 text-white fs-5">CoreAxis</span>
                        <span class="d-block text-white opacity-50" style="font-size:.65rem;letter-spacing:1.5px">FINANCIAL</span>
                    </div>
                </div>
                <p class="text-white opacity-60 small">Empowering your financial future with secure, smart, and seamless banking solutions trusted by thousands worldwide.</p>
                <div class="d-flex gap-2 mt-3">
                    <a href="#" class="social-btn"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-btn"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="social-btn"><i class="bi bi-linkedin"></i></a>
                    <a href="#" class="social-btn"><i class="bi bi-instagram"></i></a>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="text-white fw-semibold mb-3">Quick Links</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('home') }}" class="footer-link">Home</a></li>
                    <li class="mb-2"><a href="{{ route('about') }}" class="footer-link">About Us</a></li>
                    <li class="mb-2"><a href="{{ route('services') }}" class="footer-link">Services</a></li>
                    <li class="mb-2"><a href="{{ route('account.plans') }}" class="footer-link">Account Plans</a></li>
                    <li class="mb-2"><a href="{{ route('contact') }}" class="footer-link">Contact</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="text-white fw-semibold mb-3">Services</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><span class="footer-link">Personal Banking</span></li>
                    <li class="mb-2"><span class="footer-link">Business Banking</span></li>
                    <li class="mb-2"><span class="footer-link">Loan Services</span></li>
                    <li class="mb-2"><span class="footer-link">Fixed Deposits</span></li>
                    <li class="mb-2"><span class="footer-link">Fund Transfers</span></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h6 class="text-white fw-semibold mb-3">Contact Info</h6>
                <ul class="list-unstyled">
                    <li class="mb-2 d-flex gap-2"><i class="bi bi-geo-alt text-accent mt-1"></i><span class="footer-link">123 Financial District, New York, NY 10004</span></li>
                    <li class="mb-2 d-flex gap-2"><i class="bi bi-telephone text-accent mt-1"></i><span class="footer-link">+1 (800) COREAXIS</span></li>
                    <li class="mb-2 d-flex gap-2"><i class="bi bi-envelope text-accent mt-1"></i><span class="footer-link">support@coreaxis.com</span></li>
                    <li class="mb-2 d-flex gap-2"><i class="bi bi-clock text-accent mt-1"></i><span class="footer-link">Mon–Fri: 9AM – 6PM EST</span></li>
                </ul>
            </div>
        </div>
        <hr class="border-white opacity-10">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <p class="text-white opacity-50 small mb-0">© {{ date('Y') }} CoreAxis Financial Management. All rights reserved.</p>
            <div class="d-flex gap-3">
                <a href="#" class="footer-link small">Privacy Policy</a>
                <a href="#" class="footer-link small">Terms of Service</a>
                <a href="#" class="footer-link small">Security</a>
            </div>
        </div>
    </div>
</footer>
