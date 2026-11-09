<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Kelas - SMK Bagimu Negeriku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3">Tambah Data Kelas</h4>
                    <hr class="mb-4">

                    <form action="{{ route('kelas.store') }}" method="POST">
                        @csrf

                        <!-- Nama Kelas -->
                        <div class="mb-3">
                            <label for="nama_kelas" class="form-label">Nama Kelas</label>
                            <input type="text" name="nama_kelas" id="nama_kelas" class="form-control @error('nama_kelas') is-invalid @enderror" value="{{ old('nama_kelas') }}" placeholder="Contoh: X RPL 1" required>
                            @error('nama_kelas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tingkat Kelas (10 - 12) -->
                        <div class="mb-3">
                            <label for="tingkat" class="form-label">Tingkat Kelas</label>
                            <select name="tingkat" id="tingkat" class="form-select @error('tingkat') is-invalid @enderror" required>
                                <option value="" disabled selected>Pilih Tingkat Kelas</option>
                                <option value="10" {{ old('tingkat') == '10' ? 'selected' : '' }}>Kelas 10</option>
                                <option value="11" {{ old('tingkat') == '11' ? 'selected' : '' }}>Kelas 11</option>
                                <option value="12" {{ old('tingkat') == '12' ? 'selected' : '' }}>Kelas 12</option>
                            </select>
                            @error('tingkat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Jurusan (RPL, DKV, TKP, TP, Kuliner) -->
                        <div class="mb-3">
                            <label for="jurusan" class="form-label">Program Keahlian / Jurusan</label>
                            <select name="jurusan" id="jurusan" class="form-select @error('jurusan') is-invalid @enderror" required>
                                <option value="" disabled selected>Pilih Jurusan</option>
                                <option value="RPL" {{ old('jurusan') == 'RPL' ? 'selected' : '' }}>RPL (Rekayasa Perangkat Lunak)</option>
                                <option value="DKV" {{ old('jurusan') == 'DKV' ? 'selected' : '' }}>DKV (Desain Komunikasi Visual)</option>
                                <option value="TKP" {{ old('jurusan') == 'TKP' ? 'selected' : '' }}>TKP (Teknik Konstruksi dan Perumahan)</option>
                                <option value="TP" {{ old('jurusan') == 'TP' ? 'selected' : '' }}>TP (Teknik Pemesinan)</option>
                                <option value="Kuliner" {{ old('jurusan') == 'Kuliner' ? 'selected' : '' }}>Kuliner</option>
                            </select>
                            @error('jurusan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Wali Kelas -->
                        <div class="mb-3">
                            <label for="wali_kelas" class="form-label">Nama Wali Kelas</label>
                            <input type="text" name="wali_kelas" id="wali_kelas" class="form-control @error('wali_kelas') is-invalid @enderror" value="{{ old('wali_kelas') }}" placeholder="Masukkan nama lengkap beserta gelar" required>
                            @error('wali_kelas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('kelas.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan Kelas</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
