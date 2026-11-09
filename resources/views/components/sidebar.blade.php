@php
    $menu = [
        ['dashboard', 'bi-speedometer2', 'Dashboard', 'dashboard'],
        ['siswa.index', 'bi-people', 'Data Siswa', 'siswa.index'],
        ['siswa.direktori', 'bi-exclamation-triangle', 'Pusat Peringatan', 'siswa.direktori*'], // <-- Diubah di sini
        ['kelas.index', 'bi-building', 'Kelas', 'kelas.*'],
        ['jenis-pelanggaran.index', 'bi-list-check', 'Jenis Pelanggaran', 'jenis-pelanggaran.*'],
        ['pelanggaran.create', 'bi-pencil-square', 'Catat Pelanggaran', 'pelanggaran.create'],
        ['pelanggaran.index', 'bi-clock-history', 'Riwayat', 'pelanggaran.index'],
        ['laporan', 'bi-printer', 'Laporan', 'laporan'],
    ];
@endphp
<nav class="offcanvas-lg offcanvas-start bg-white border-end p-3 no-print flex-shrink-0" id="sidebar" tabindex="-1">
    <div class="offcanvas-header d-lg-none">
        <h5 class="mb-0">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebar"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav flex-column gap-1 w-100">
            @foreach ($menu as [$route, $icon, $label, $pattern])
                <li>
                    <a class="nav-link text-dark {{ request()->routeIs($pattern) ? 'active' : '' }}" href="{{ route($route) }}">
                        <i class="bi {{ $icon }} me-2"></i>{{ $label }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</nav>
