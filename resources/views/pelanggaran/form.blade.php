@extends('layouts.app')

@section('title', 'Catat Pelanggaran')

@section('content')
    <div class="container-fluid px-4">
        <h4 class="fw-bold mb-3">{{ isset($pelanggaran) && $pelanggaran->exists ? 'Edit' : 'Catat' }} Pelanggaran</h4>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="{{ isset($pelanggaran) && $pelanggaran->exists ? route('pelanggaran.update', $pelanggaran) : route('pelanggaran.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (isset($pelanggaran) && $pelanggaran->exists)
                        @method('PUT')
                    @endif

                    <!-- Baris 1: Nama Siswa (Live Search) -->
                    <div class="mb-3 position-relative">
                        <label for="search_siswa" class="form-label">Nama Siswa</label>
                        <input type="text" id="search_siswa" class="form-control @error('siswa_id') is-invalid @enderror" placeholder="Ketik nama atau NIS siswa..." value="{{ old('search_siswa', isset($pelanggaran) && $pelanggaran->siswa ? $pelanggaran->siswa->nama : '') }}" autocomplete="off" required>

                        <!-- Input tersembunyi untuk menyimpan ID siswa yang dikirim ke database -->
                        <input type="hidden" name="siswa_id" id="siswa_id" value="{{ old('siswa_id', isset($pelanggaran) ? $pelanggaran->siswa_id : '') }}" required>

                        <!-- Dropdown daftar hasil pencarian -->
                        <ul id="siswa-list" class="list-group position-absolute w-100 shadow-sm" style="z-index: 1000; max-height: 200px; overflow-y: auto; display: none;"></ul>

                        @error('siswa_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Baris 2: Kolom Terpisah untuk Kelas dan Jurusan -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kelas</label>
                            <input type="text" id="info_kelas" class="form-control bg-white" placeholder="Otomatis terisi..." value="{{ isset($pelanggaran) && $pelanggaran->siswa && $pelanggaran->siswa->kelas ? $pelanggaran->siswa->kelas->nama_kelas : '' }}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jurusan</label>
                            <input type="text" id="info_jurusan" class="form-control bg-white" placeholder="Otomatis terisi..." value="{{ isset($pelanggaran) && $pelanggaran->siswa && $pelanggaran->siswa->kelas ? $pelanggaran->siswa->kelas->jurusan : '' }}" readonly>
                        </div>
                    </div>

                    <!-- Jenis Pelanggaran -->
                    <div class="mb-3">
                        <label for="jenis_pelanggaran_id" class="form-label">Jenis Pelanggaran</label>
                        <select name="jenis_pelanggaran_id" id="jenis_pelanggaran_id" class="form-select" required>
                            <!-- Tempel di sini -->
                            <option value="" disabled selected>-- Pilih Jenis Pelanggaran --</option>

                            @foreach ($jenis as $item)
                                <option value="{{ $item->id }}" {{ old('jenis_pelanggaran_id', $pelanggaran->jenis_pelanggaran_id ?? '') == $item->id ? 'selected' : '' }}>
                                    {{ $item->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Tanggal & Waktu Kejadian -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tanggal" class="form-label">Tanggal Kejadian</label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', isset($pelanggaran) ? $pelanggaran->tanggal : date('Y-m-d')) }}" required>
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="waktu" class="form-label">Waktu Kejadian</label>
                            <input type="time" name="waktu" id="waktu" class="form-control @error('waktu') is-invalid @enderror" value="{{ old('waktu', isset($pelanggaran) ? $pelanggaran->waktu : date('H:i')) }}" required>
                            @error('waktu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan Tambahan</label>
                        <textarea name="keterangan" id="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror" placeholder="Kronologi atau catatan pelanggaran...">{{ old('keterangan', isset($pelanggaran) ? $pelanggaran->keterangan : '') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Bukti / Foto -->
                    <div class="mb-3">
                        <label for="bukti" class="form-label">Bukti / Foto (Opsional)</label>
                        <input type="file" name="bukti" id="bukti" class="form-control @error('bukti') is-invalid @enderror">
                        @error('bukti')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('pelanggaran.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan Pelanggaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('search_siswa');
                const siswaList = document.getElementById('siswa-list');
                const siswaIdInput = document.getElementById('siswa_id');
                const infoKelas = document.getElementById('info_kelas');
                const infoJurusan = document.getElementById('info_jurusan');

                // Fungsi saat mengetik di kolom pencarian nama siswa
                searchInput.addEventListener('input', function() {
                    let query = this.value.trim();

                    if (query.length > 1) {
                        fetch(`{{ route('api.siswa.search') }}?q=${query}`)
                            .then(response => response.json())
                            .then(data => {
                                siswaList.innerHTML = '';
                                if (data.length > 0) {
                                    siswaList.style.display = 'block';
                                    data.forEach(siswa => {
                                        let li = document.createElement('li');
                                        li.className = 'list-group-item list-group-item-action';
                                        li.style.cursor = 'pointer';

                                        let namaKelas = siswa.kelas ? siswa.kelas.nama_kelas : '-';
                                        let jurusan = siswa.kelas ? siswa.kelas.jurusan : '-';

                                        li.innerHTML = `<strong>${siswa.nama}</strong> <small class="text-muted">(NIS: ${siswa.nis} | Kelas: ${namaKelas} - ${jurusan})</small>`;

                                        // Ketika salah satu nama diklik dari daftar
                                        li.addEventListener('click', function() {
                                            searchInput.value = siswa.nama;
                                            siswaIdInput.value = siswa.id;

                                            // Otomatis isi informasi ke masing-masing kolom terpisah
                                            infoKelas.value = namaKelas;
                                            infoJurusan.value = jurusan;

                                            siswaList.style.display = 'none';
                                        });

                                        siswaList.appendChild(li);
                                    });
                                } else {
                                    siswaList.style.display = 'none';
                                }
                            });
                    } else {
                        siswaList.style.display = 'none';
                        siswaIdInput.value = '';
                        infoKelas.value = '';
                        infoJurusan.value = '';
                    }
                });

                // Tutup dropdown jika mengklik di luar area input pencarian
                document.addEventListener('click', function(e) {
                    if (!searchInput.contains(e.target) && !siswaList.contains(e.target)) {
                        siswaList.style.display = 'none';
                    }
                });
            });
        </script>
    @endpush
@endsection
