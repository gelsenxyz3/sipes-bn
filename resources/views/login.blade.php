<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SMK Bagimu Negeriku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body style="background:linear-gradient(135deg,#0d6efd,#6610f2)">
<div class="container min-vh-100 d-flex align-items-center justify-content-center py-4">
    <div style="max-width:420px;width:100%">
        <x-alert />
        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <!-- Logo SMK Bagimu Negeriku -->
                    <img src="{{ asset('images/logo-smk.png') }}" alt="Logo SMK Bagimu Negeriku" style="height: 75px;" class="mb-3">
                    <h4 class="fw-bold mb-0">SMK Bagimu Negeriku</h4>
                    <div class="text-muted small">Sistem Informasi Pelanggaran Siswa</div>
                    <div class="fw-semibold text-primary small mt-2">“BERBUDI - BERKUALITAS - BERDAYA - BERHASIL”</div>
                </div>

                <form method="POST" action="{{ route('login.process') }}">
                    @csrf

                    <!-- Input Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <div class="invalid-feedback d-block text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Input Password dengan Toggle Lihat/Sembunyikan -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="bi bi-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Checkbox Syarat & Ketentuan (Wajib Dicentang) -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" name="terms" id="terms" value="1" required>
                            <label class="form-check-label text-secondary small" for="terms">
                                Saya setuju dengan Syarat & Ketentuan sistem ini.
                            </label>
                        </div>
                        @error('terms')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <x-button class="w-100">Login</x-button>
                </form>

                <!-- Link Menuju Halaman Registrasi -->
                <div class="text-center mt-3">
                    <small class="text-muted">Belum punya akun? <a href="{{ route('register') }}" class="text-decoration-none fw-bold">Daftar di sini</a></small>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Skrip untuk tombol Lihat/Sembunyikan Password
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

    togglePassword.addEventListener('click', function () {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        toggleIcon.classList.toggle('bi-eye');
        toggleIcon.classList.toggle('bi-eye-slash');
    });

    // Notifikasi hilang otomatis
    document.querySelectorAll('.alert').forEach(a => setTimeout(() => bootstrap.Alert.getOrCreateInstance(a).close(), 4000));
</script>
</body>
</html>
