@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-4">
        <!-- Header Halaman -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800 fw-bold">Pusat Peringatan</h1>
                <p class="text-muted small mb-0">Pusat pengelolaan dan evaluasi data kedisiplinan siswa SMK Bagimu Negeriku.</p>
            </div>
        </div>

        <!-- Card Pencarian dan Filter -->
        <div class="card border-0 shadow-sm rounded-3 mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('siswa.direktori') }}" class="row g-3 align-items-end">
                    <div class="col-lg-3 col-md-4">
                        <label class="form-label small fw-bold">Pencarian</label>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari Nama, NIS, atau NISN...">
                    </div>
                    <div class="col-lg-2 col-md-3">
                        <label class="form-label small fw-bold">Kelas</label>
                        <select name="kelas_id" class="form-select">
                            <option value="">Semua Kelas</option>
                            @foreach ($kelasList as $k)
                                <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas ?? $k->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-3">
                        <label class="form-label small fw-bold">Kategori</label>
                        <select name="kategori" class="form-select">
                            <option value="">Semua Kategori</option>
                            <option value="Ringan" {{ request('kategori') == 'Ringan' ? 'selected' : '' }}>Ringan</option>
                            <option value="Sedang" {{ request('kategori') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="Berat" {{ request('kategori') == 'Berat' ? 'selected' : '' }}>Berat</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-2">
                        <label class="form-label small fw-bold">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select">
                            <option value="">Semua</option>
                            <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-lg-1 col-md-2">
                        <label class="form-label small fw-bold">Tampil</label>
                        <select name="per_page" class="form-select">
                            @foreach ([10, 25, 50, 100] as $val)
                                <option value="{{ $val }}" {{ request('per_page', 10) == $val ? 'selected' : '' }}>{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Filter</button>
                        <a href="{{ route('siswa.direktori') }}" class="btn btn-outline-secondary" title="Reset Filter"><i class="fas fa-redo"></i></a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Data Pelanggaran Siswa -->
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase fs-7">
                            <tr>
                                <th class="ps-4">No</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Jurusan</th>
                                <th>Kategori</th>
                                <th>Jenis Pelanggaran</th>
                                <th>Poin</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pelanggarans as $index => $p)
                                <tr>
                                    <td class="ps-4">{{ $pelanggarans->firstItem() + $index }}</td>
                                    <td>
                                        <span class="fw-bold text-dark">{{ $p->siswa->nama_lengkap ?? $p->siswa->nama }}</span><br>
                                        <small class="text-muted">NIS: {{ $p->siswa->nis ?? '-' }}</small>
                                    </td>
                                    <td>{{ $p->siswa->kelas->nama_kelas ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1">
                                            {{ $p->siswa->kelas->jurusan ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $kat = $p->jenis->kategori ?? 'Ringan';
                                            $badgeColor = match(strtolower($kat)) {
                                                'ringan' => 'bg-warning text-dark',
                                                'sedang' => 'bg-info text-dark',
                                                'berat' => 'bg-danger text-white',
                                                default => 'bg-secondary text-white'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeColor }} px-2 py-1">{{ $kat }}</span>
                                    </td>
                                    <td>{{ $p->jenis->nama ?? '-' }}</td>
                                    <td><span class="fw-bold text-danger">+{{ $p->poin }}</span></td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('siswa.direktori.show', $p->siswa_id) }}" class="btn btn-sm btn-info text-white shadow-sm" title="Profil 360° Siswa">
                                            <i class="fas fa-eye me-1"></i> Detail 360°
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">Data pelanggaran siswa tidak ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Pagination -->
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="small text-muted">
                        Menampilkan {{ $pelanggarans->firstItem() ?? 0 }} sampai {{ $pelanggarans->lastItem() ?? 0 }} dari total {{ $pelanggarans->total() }} data pelanggaran
                    </div>
                    <div>
                        {{ $pelanggarans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
