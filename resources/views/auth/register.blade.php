<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrasi - SMK Bagimu Negeriku</title>
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
                    <div class="fw-semibold text-primary small mt-2">“BERBUDI - BERKUALITAS - BERDAYA BERHASIL”</div>
                </div>

                <form method="POST" action="{{ route('register.process') }}">
                    @csrf
                    <x-form-input name="name" label="Nama Lengkap" type="text" autofocus />
                    <x-form-input name="email" label="Email" type="email" />
                    <x-form-input name="password" label="Password" type="password" />
                    <x-form-input name="password_confirmation" label="Konfirmasi Password" type="password" />

                    <x-button class="w-100 mt-3">Daftar</x-button>
                </form>

                <div class="text-center mt-3">
                    <small class="text-muted">Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Login di sini</a></small>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>document.querySelectorAll('.alert').forEach(a => setTimeout(() => bootstrap.Alert.getOrCreateInstance(a).close(), 4000));</script>
</body>
</html>
