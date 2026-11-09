<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - SIPES</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6fb; }
        @media (min-width: 992px) { #sidebar { width: 250px; min-height: calc(100vh - 57px); } }
        .nav-link.active { background: #e7efff; color: #0d6efd !important; font-weight: 600; border-radius: .6rem; }
        @media print { .no-print { display: none !important; } body { background: #fff; } .card { box-shadow: none !important; } }
    </style>
</head>
<body>
    <x-navbar />
    <div class="d-flex">
        <x-sidebar />
        <main class="flex-grow-1 p-3 p-md-4" style="min-width:0">
            <x-alert />
            @yield('content')
        </main>
    </div>

    <x-modal id="deleteModal" title="Konfirmasi Hapus">
        <form id="deleteForm" method="POST">
            @csrf @method('DELETE')
            <div class="modal-body">Yakin ingin menghapus data ini? Tindakan tidak dapat dibatalkan.</div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <x-button color="danger">Ya, Hapus</x-button>
            </div>
        </form>
    </x-modal>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
        // notifikasi hilang otomatis
        document.querySelectorAll('.alert').forEach(a => setTimeout(() => bootstrap.Alert.getOrCreateInstance(a).close(), 4000));
        // modal konfirmasi hapus
        document.addEventListener('click', e => {
            const b = e.target.closest('[data-delete]');
            if (!b) return;
            document.getElementById('deleteForm').action = b.dataset.delete;
            new bootstrap.Modal('#deleteModal').show();
        });
        // search & filter realtime
        let timer;
        document.querySelectorAll('form[data-autosubmit] input[type=text]').forEach(i =>
            i.addEventListener('input', () => { clearTimeout(timer); timer = setTimeout(() => i.form.submit(), 500); }));
        document.querySelectorAll('form[data-autosubmit] select, form[data-autosubmit] input[type=date]').forEach(i =>
            i.addEventListener('change', () => i.form.submit()));
        const s = document.querySelector('form[data-autosubmit] input[name=q]');
        if (s && s.value) { s.focus(); s.setSelectionRange(s.value.length, s.value.length); }
    </script>
    @stack('scripts')
</body>
</html>
