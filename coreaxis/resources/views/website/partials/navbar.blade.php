<nav class="navbar navbar-expand-lg fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            <div class="brand-icon"><i class="bi bi-bank2"></i></div>
            <div>
                <span class="fw-800 text-white fs-5">CoreAxis</span>
                <span class="d-block text-white opacity-75" style="font-size:.65rem;letter-spacing:1.5px;margin-top:-4px">FINANCIAL</span>
            </div>
        </a>
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-expanded="false" aria-label="Toggle navigation">
            <i class="bi bi-list text-white fs-3"></i>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav mx-auto gap-1">
                <li class="nav-item"><a class="nav-link text-white px-3 {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link text-white px-3" href="{{ route('about') }}">About</a></li>
                <li class="nav-item"><a class="nav-link text-white px-3" href="{{ route('services') }}">Services</a></li>
                <li class="nav-item"><a class="nav-link text-white px-3" href="{{ route('account.plans') }}">Account Plans</a></li>
                <li class="nav-item"><a class="nav-link text-white px-3" href="{{ route('contact') }}">Contact</a></li>
            </ul>
            <div class="d-flex gap-2 mt-3 mt-lg-0">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm px-4 fw-semibold">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm px-3">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-light btn-sm px-4 fw-semibold text-primary">Get Started</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
