@extends('layouts.app')
@section('title', 'Data Kelas')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0">Data Kelas</h4>
    <button class="btn btn-primary" data-kelas="{{ json_encode(['url' => route('kelas.store'), 'method' => 'POST']) }}">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kelas
    </button>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $e)
            <div>{{ $e }}</div>
        @endforeach
    </div>
@endif

<x-card>
    <x-table class="align-middle">
        <x-slot:head>
            <th class="ps-4">No</th>
            <th>Nama Kelas</th>
            <th>Jurusan</th>
            <th>Tingkat</th>
            <th>Wali Kelas</th>
            <th class="text-center">Jumlah Siswa</th>
            <th class="text-end pe-4">Aksi</th>
        </x-slot:head>
        @forelse ($kelas as $k)
            <tr>
                <td class="ps-4">{{ $loop->iteration }}</td>
                <td><span class="fw-bold text-dark">{{ $k->nama_kelas }}</span></td>
                <td>{{ $k->jurusan }}</td>
                <td>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1">{{ $k->tingkat }}</span>
                </td>
                <td>{{ $k->wali_kelas }}</td>
                <td class="text-center">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1">{{ $k->siswa_count ?? 0 }} Siswa</span>
                </td>
                <td class="text-end pe-4 text-nowrap">
                    <div class="d-flex justify-content-end gap-1">
                        <a href="{{ route('kelas.show', $k) }}" class="btn btn-sm btn-outline-info" title="Detail Kelas">
                            <i class="bi bi-people"></i>
                        </a>
                        <button class="btn btn-sm btn-outline-warning" data-kelas="{{ json_encode(['url' => route('kelas.update', $k), 'method' => 'PUT', 'data' => $k->only('nama_kelas', 'jurusan', 'tingkat', 'wali_kelas')]) }}" title="Edit Kelas">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger" data-delete="{{ route('kelas.destroy', $k) }}" title="Hapus Kelas">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada data kelas.</td></tr>
        @endforelse
    </x-table>
</x-card>

<x-modal id="kelasModal" title="Form Kelas">
    <form id="kelasForm" method="POST">
        @csrf
        <input type="hidden" name="_method" id="kelasMethod" value="POST">
        <div class="modal-body">
            <x-form-input name="nama_kelas" label="Nama Kelas" placeholder="contoh: X RPL 1" />
            <x-form-input name="jurusan" label="Jurusan" />
            <x-form-select name="tingkat" label="Tingkat" :options="['X' => 'X', 'XI' => 'XI', 'XII' => 'XII']" />
            <x-form-input name="wali_kelas" label="Wali Kelas" />
        </div>
        <div class="modal-footer border-0">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
            <x-button>Simpan</x-button>
        </div>
    </form>
</x-modal>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-kelas]').forEach(btn => btn.addEventListener('click', () => {
        const c = JSON.parse(btn.dataset.kelas), f = document.getElementById('kelasForm');
        f.action = c.url;
        document.getElementById('kelasMethod').value = c.method;
        ['nama_kelas', 'jurusan', 'tingkat', 'wali_kelas'].forEach(n => f.elements[n].value = c.data ? c.data[n] : (n === 'jurusan' ? 'RPL' : ''));
        new bootstrap.Modal('#kelasModal').show();
    }));
</script>
@endpush
