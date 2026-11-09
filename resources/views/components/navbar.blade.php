<nav class="navbar bg-white border-bottom sticky-top px-3 no-print">
    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-light d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#sidebar"><i class="bi bi-list"></i></button>
        <a class="navbar-brand fw-bold text-primary mb-0 d-flex align-items-center text-decoration-none" href="{{ route('dashboard') }}">
            <img src="{{ asset('images/logo-smk.png') }}" alt="Logo SMK Bagimu Negeriku" style="height: 36px;" class="me-2">
            <span>SMK Bagimu Negeriku</span>
        </a>
    </div>
    <form method="POST" action="{{ route('logout') }}" class="d-flex align-items-center gap-3">
        @csrf
        <span class="text-muted d-none d-md-inline"><i class="bi bi-person-circle"></i> {{ auth()->user()->name }}</span>
        <x-button color="outline-danger" class="btn-sm"><i class="bi bi-box-arrow-right"></i> Logout</x-button>
    </form>
</nav>
