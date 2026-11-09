@extends('layouts.app')
@section('title', 'Data Siswa')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0">Data Siswa</h4>
    <a href="{{ route('siswa.create') }}" class="btn btn-primary shadow-sm"><i class="bi bi-plus-lg"></i> Tambah Siswa</a>
</div>

<x-card>
    <!-- Form Filter dan Pencarian yang Sejajar & Responsif -->
    <form class="row g-2 mb-3 align-items-end" data-autosubmit>
        <div class="col-md-6">
            <label class="form-label small fw-bold">Pencarian</label>
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama / NIS / NISN...">
        </div>
        <div class="col-md-4">
            <label class="form-label small fw-bold">Kelas</label>
            <select name="kelas_id" class="form-select">
                <option value="">Semua Kelas</option>
                @foreach ($kelas as $k)
                    <option value="{{ $k->id }}" @selected(request('kelas_id') == $k->id)>{{ $k->nama_kelas }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <a href="{{ route('siswa.index') }}" class="btn btn-light border w-100"><i class="bi bi-arrow-counterclockwise me-1"></i> Reset</a>
        </div>
    </form>

    <!-- Tabel Data Siswa -->
    <x-table>
        <x-slot:head>
            <th class="py-3" style="width: 5%;">No</th>
            <th class="py-3" style="width: 20%;">NIS / NISN</th>
            <th class="py-3" style="width: 30%;">Nama Siswa</th>
            <th class="py-3" style="width: 15%;">Kelas</th>
            <th class="py-3" style="width: 15%;">Jenis Kelamin</th>
            <th class="text-end py-3" style="width: 15%;">Aksi</th>
        </x-slot:head>

        @forelse ($siswas as $s)
            <tr>
                <td class="fw-bold text-muted">{{ $siswas->firstItem() + $loop->index }}</td>
                <td>
                    <div class="fw-bold text-dark">{{ $s->nis }}</div>
                    <small class="text-muted">NISN: {{ $s->nisn ?? '-' }}</small>
                </td>
                <td>
                    <div class="fw-bold text-dark">{{ $s->nama_lengkap ?? $s->nama }}</div>
                </td>
                <td>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1">
                        {{ $s->kelas->nama_kelas ?? '-' }}
                    </span>
                </td>
                <td>
                    @if(in_array($s->jenis_kelamin, ['L', 'Laki-laki', 'l']))
                        <span class="text-info fw-semibold"><i class="bi bi-gender-male me-1"></i> Laki-laki</span>
                    @else
                        <span class="text-danger fw-semibold"><i class="bi bi-gender-female me-1"></i> Perempuan</span>
                    @endif
                </td>
                <td class="text-end text-nowrap">
                    <div class="d-flex justify-content-end gap-1">
                        <a href="{{ route('siswa.show', $s) }}" class="btn btn-sm btn-outline-primary" title="Detail"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('siswa.edit', $s) }}" class="btn btn-sm btn-outline-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                        <button type="button" class="btn btn-sm btn-outline-danger" data-delete="{{ route('siswa.destroy', $s) }}" title="Hapus"><i class="bi bi-trash"></i></button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-muted py-4">Data siswa tidak ditemukan.</td>
            </tr>
        @endforelse
    </x-table>

    <!-- Pagination & Info -->
    <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="small text-muted">
            Menampilkan {{ $siswas->firstItem() ?? 0 }} sampai {{ $siswas->lastItem() ?? 0 }} dari total {{ $siswas->total() }} data siswa
        </div>
        <div>
            {{ $siswas->links('pagination::bootstrap-5') }}
        </div>
    </div>
</x-card>
@endsection
