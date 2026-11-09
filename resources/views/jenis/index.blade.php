@extends('layouts.app')
@section('title', 'Jenis Pelanggaran')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Jenis Pelanggaran</h4>
            <p class="text-muted small mb-0">Daftar master data jenis pelanggaran dan bobot poin.</p>
        </div>
        <a href="{{ route('jenis-pelanggaran.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Jenis Pelanggaran
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase fs-7 text-secondary">
                        <tr>
                            <th class="py-3 px-4" style="width: 5%;">No</th>
                            <th class="py-3">Pelanggaran</th>
                            <th class="py-3">Kategori</th>
                            <th class="py-3">Poin</th>
                            <th class="py-3">Tingkat</th>
                            <th class="py-3 text-end px-4" style="width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jenis as $index => $item)
                            <tr>
                                <td class="px-4">{{ $index + 1 }}</td>
                                <td class="fw-semibold text-dark">{{ $item->nama }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2 py-1">{{ $item->kategori }}</span>
                                </td>
                                {{-- Kolom Poin Terpisah --}}
                                <td>
                                    <span class="fw-bold text-primary">{{ $item->poin }}</span>
                                </td>
                                {{-- Kolom Tingkat Terpisah --}}
                                <td>
                                    @php
                                        $badgeColor = 'success';
                                        if($item->tingkat == 'Sedang') $badgeColor = 'warning text-dark';
                                        if($item->tingkat == 'Berat') $badgeColor = 'danger';
                                    @endphp
                                    <span class="badge bg-{{ $badgeColor }}">{{ $item->tingkat }}</span>
                                </td>
                                <td class="text-end px-4">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('jenis-pelanggaran.edit', $item->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('jenis-pelanggaran.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada data jenis pelanggaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
