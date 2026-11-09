@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Header & Tombol Kembali -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800 fw-bold">Profil Direktori Siswa 360°</h1>
            <p class="text-muted small mb-0">Informasi komprehensif dan evaluasi kedisiplinan siswa.</p>
        </div>
        <div>
            <a href="{{ route('siswa.direktori') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Direktori
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Kolom Kiri: Profil Singkat & Status SIPES -->
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm rounded-3 text-center p-4 mb-4">
                <div class="card-body">
                    <img src="{{ $siswa->foto ? asset('storage/' . $siswa->foto) : asset('images/default-user.png') }}"
                         alt="Foto Profil" class="rounded-circle img-thumbnail shadow-sm mb-3" width="120" height="120" style="object-fit: cover;">
                    <h4 class="fw-bold text-dark mb-1">{{ $siswa->nama_lengkap ?? $siswa->nama }}</h4>
                    <p class="text-muted mb-2">NIS: {{ $siswa->nis ?? '-' }} | NISN: {{ $siswa->nisn ?? '-' }}</p>
                    <span class="badge bg-primary px-3 py-2 fs-6 mb-3">
                        {{ $siswa->kelas->nama_kelas ?? $siswa->kelas->nama ?? 'Kelas Tidak Diketahui' }}
                    </span>

                    <hr class="my-3">

                    <!-- Status Keamanan SIPES -->
                    @php
                        $status = $siswa->status;
                    @endphp
                    <div class="p-3 rounded-3 bg-light text-start">
                        <span class="text-muted small d-block mb-1">Status Keamanan & Poin SIPES:</span>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-{{ $status[1] }} px-3 py-2 fs-6">{{ $status[0] }}</span>
                            <h3 class="fw-bold text-danger mb-0">{{ $siswa->total_poin }} <small class="fs-6 text-muted">Poin</small></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Detail Biodata & Riwayat Pelanggaran -->
        <div class="col-xl-8">
            <!-- Informasi Pribadi -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-user-circle me-2 text-primary"></i>Informasi Lengkap Siswa</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <span class="text-muted small d-block">Jenis Kelamin</span>
                            <span class="fw-bold">{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted small d-block">Tempat, Tanggal Lahir</span>
                            <span class="fw-bold">{{ $siswa->tempat_lahir ?? '-' }}, {{ $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('d-m-Y') : '-' }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted small d-block">Nomor Telepon</span>
                            <span class="fw-bold">{{ $siswa->telepon ?? $siswa->no_hp ?? '-' }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="text-muted small d-block">Nama Orang Tua / Wali</span>
                            <span class="fw-bold">{{ $siswa->nama_wali ?? $siswa->orang_tua ?? '-' }}</span>
                        </div>
                        <div class="col-12">
                            <span class="text-muted small d-block">Alamat Lengkap</span>
                            <span class="fw-bold">{{ $siswa->alamat ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Riwayat Pelanggaran SIPES (360 View) -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-exclamation-triangle me-2 text-warning"></i>Riwayat Pelanggaran & Evaluasi Siswa</h5>
                    <span class="badge bg-secondary">{{ $siswa->pelanggaran->count() }} Kasus</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light fs-7 text-uppercase">
                                <tr>
                                    <th class="ps-4">No</th>
                                    <th>Tanggal</th>
                                    <th>Jenis Pelanggaran</th>
                                    <th>Poin</th>
                                    <th class="pe-4">Petugas / Pencatat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($siswa->pelanggaran as $index => $p)
                                    <tr>
                                        <td class="ps-4">{{ $index + 1 }}</td>
                                        <td>{{ $p->tanggal ? $p->tanggal->format('d/m/Y') : '-' }}</td>
                                        <td>
                                            <span class="fw-bold text-dark">{{ $p->jenis->nama_pelanggaran ?? $p->jenis->nama ?? 'Pelanggaran Disiplin' }}</span>
                                            @if($p->keterangan)
                                                <br><small class="text-muted">{{ $p->keterangan }}</small>
                                            @endif
                                        </td>
                                        <td><span class="badge bg-danger">{{ $p->poin }} Poin</span></td>
                                        <td class="pe-4">{{ $p->petugas->name ?? 'Admin SIPES' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Siswa ini tidak memiliki riwayat pelanggaran. Pertahankan prestasi! 🎉</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
