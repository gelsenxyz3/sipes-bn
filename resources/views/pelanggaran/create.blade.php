<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catat Pelanggaran - SMK Bagimu Negeriku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-3">Catat Pelanggaran Siswa</h4>
                        <hr class="mb-4">

                        <form action="{{ route('pelanggaran.store') }}" method="POST">
                            @csrf

                            <!-- Baris: Kelas (Otomatis) & Nama Siswa (Live Search) -->
                            <div class="row">
                                <!-- Kolom Nama Siswa dengan Fitur Pencarian Otomatis -->
                                <div class="col-md-6 mb-3 position-relative">
                                    <label for="search_siswa" class="form-label">Nama Siswa</label>
                                    <input type="text" id="search_siswa" class="form-control" placeholder="Ketik nama siswa..." autocomplete="off" required>

                                    <!-- Input hidden untuk menyimpan ID siswa yang dikirim ke database -->
                                    <input type="hidden" name="siswa_id" id="siswa_id" required>

                                    <!-- Dropdown hasil pencarian live search -->
                                    <ul id="siswa-list" class="list-group position-absolute w-100 shadow-sm" style="z-index: 1000; max-height: 200px; overflow-y: auto; display: none;"></ul>
                                </div>

                                <!-- Kolom Kelas (Tampil otomatis berdasarkan data siswa) -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kelas & Jurusan</label>
                                    <input type="text" id="info_kelas_jurusan" class="form-control bg-white" placeholder="Otomatis terisi..." readonly>
                                </div>
                            </div>

                            <!-- Jenis Pelanggaran -->
                            <div class="mb-3">
                                <label for="jenis_pelanggaran_id" class="form-label">Jenis Pelanggaran</label>
                                <select name="jenis_pelanggaran_id" id="jenis_pelanggaran_id" class="form-select" required>
                                    <option value="">-- Pilih Jenis Pelanggaran --</option>

                                    {{-- TAMBAHKAN KODE INI AGAR DATANYA MUNCUL --}}
                                    @foreach ($jenis as $item)
                                        <option value="{{ $item->id }}" {{ old('jenis_pelanggaran_id', $pelanggaran->jenis_pelanggaran_id ?? '') == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama }} (Kategori: {{ $item->kategori }} - {{ $item->poin }} Poin)
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            <!-- Tanggal & Waktu Kejadian -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal" class="form-label">Tanggal Kejadian</label>
                                    <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="waktu" class="form-label">Waktu Kejadian</label>
                                    <input type="time" name="waktu" id="waktu" class="form-control" value="{{ date('H:i') }}" required>
                                </div>
                            </div>

                            <!-- Keterangan -->
                            <div class="mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea name="keterangan" id="keterangan" rows="3" class="form-control" placeholder="Catatan tambahan..."></textarea>
                            </div>

                            <!-- Bukti / Foto -->
                            <div class="mb-3">
                                <label for="bukti" class="form-label">Bukti / Foto (opsional)</label>
                                <input type="file" name="bukti" id="bukti" class="form-control">
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('pelanggaran.index') }}" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search_siswa');
            const siswaList = document.getElementById('siswa-list');
            const siswaIdInput = document.getElementById('siswa_id');
            const infoKelasJurusan = document.getElementById('info_kelas_jurusan');

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

                                    // Ketika salah satu nama diklik dari daftar rekomendasi
                                    li.addEventListener('click', function() {
                                        searchInput.value = siswa.nama;
                                        siswaIdInput.value = siswa.id;

                                        // Otomatis isi informasi kelas dan jurusan
                                        infoKelasJurusan.value = `${namaKelas} (${jurusan})`;

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
                    infoKelasJurusan.value = '';
                }
            });

            // Tutup daftar jika mengklik di luar area input pencarian
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !siswaList.contains(e.target)) {
                    siswaList.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>
