@extends('layouts.app')
@section('title', $jenis->exists ? 'Edit Jenis Pelanggaran' : 'Tambah Jenis Pelanggaran')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">{{ $jenis->exists ? 'Edit Jenis Pelanggaran' : 'Tambah Jenis Pelanggaran' }}</h4>
            <p class="text-muted small mb-0">Kelola master data jenis pelanggaran kedisiplinan siswa.</p>
        </div>
        <a href="{{ route('jenis-pelanggaran.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            {{-- Perbaikan ada di baris action dan method di bawah ini --}}
            <form action="{{ $jenis->exists ? route('jenis-pelanggaran.update', $jenis->id) : route('jenis-pelanggaran.store') }}" method="POST">
                @csrf
                @if($jenis->exists)
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="nama" class="form-label fw-semibold">Nama Pelanggaran</label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $jenis->nama ?? '') }}" placeholder="Contoh: Terlambat hadir di sekolah" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="kategori" class="form-label fw-semibold">Kategori</label>
                    <select class="form-select @error('kategori') is-invalid @enderror" id="kategori" name="kategori" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach(['Kedisiplinan', 'Kerapian', 'Kesopanan dan Etika', 'Ketertiban', 'Akademik', 'Kebersihan'] as $kat)
                            <option value="{{ $kat }}" {{ old('kategori', $jenis->kategori ?? '') == $kat ? 'selected' : '' }}>
                                {{ $kat }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="poin" class="form-label fw-semibold">Poin</label>
                    <input type="number" class="form-control @error('poin') is-invalid @enderror" id="poin" name="poin" value="{{ old('poin', $jenis->poin ?? '') }}" placeholder="Masukkan jumlah poin" min="1" required>
                    @error('poin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="tingkat" class="form-label fw-semibold">Tingkat (Otomatis)</label>
                    <input type="text" class="form-control bg-light" id="tingkat" name="tingkat" readonly value="{{ old('tingkat', $jenis->tingkat ?? '') }}" placeholder="Akan terisi otomatis berdasarkan poin">
                    <div class="form-text text-muted">Ringan (≤10 poin), Sedang (11-25 poin), Berat (>25 poin).</div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('jenis-pelanggaran.index') }}" class="btn btn-light px-4">Batal</a>
                    <button type="submit" class="btn btn-primary px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Script otomatis menentukan tingkat berdasarkan poin
    document.getElementById('poin').addEventListener('input', function() {
        let poin = parseInt(this.value) || 0;
        let tingkatInput = document.getElementById('tingkat');
        if (poin <= 10) {
            tingkatInput.value = 'Ringan';
        } else if (poin <= 25) {
            tingkatInput.value = 'Sedang';
        } else {
            tingkatInput.value = 'Berat';
        }
    });

    // Jalankan saat halaman dimuat jika sudah ada nilai poin (untuk mode Edit)
    window.addEventListener('DOMContentLoaded', () => {
        let poinVal = document.getElementById('poin').value;
        if(poinVal && !document.getElementById('tingkat').value) {
            let poin = parseInt(poinVal);
            let tingkatInput = document.getElementById('tingkat');
            if (poin <= 10) tingkatInput.value = 'Ringan';
            else if (poin <= 25) tingkatInput.value = 'Sedang';
            else tingkatInput.value = 'Berat';
        }
    });
</script>
@endpush
@endsection
