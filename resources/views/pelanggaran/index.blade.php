@extends('layouts.app')
@section('title', 'Riwayat Pelanggaran')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0">Riwayat Pelanggaran</h4>
    <a href="{{ route('pelanggaran.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Catat Pelanggaran</a>
</div>
<x-card>
    <form class="row g-2 mb-3" data-autosubmit>
        <div class="col-md-3"><input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama siswa..."></div>
        <div class="col-md-2"><select name="siswa_id" class="form-select"><option value="">Semua Siswa</option>
            @foreach ($siswas as $s)<option value="{{ $s->id }}" @selected(request('siswa_id') == $s->id)>{{ $s->nama }}</option>@endforeach</select></div>
        <div class="col-md-2"><select name="kelas_id" class="form-select"><option value="">Semua Kelas</option>
            @foreach ($kelas as $k)<option value="{{ $k->id }}" @selected(request('kelas_id') == $k->id)>{{ $k->nama_kelas }}</option>@endforeach</select></div>
        <div class="col-md-2"><select name="kategori" class="form-select"><option value="">Semua Kategori</option>
            @foreach (App\Models\JenisPelanggaran::KATEGORI as $k)<option @selected(request('kategori') == $k)>{{ $k }}</option>@endforeach</select></div>
        <div class="col-md-2"><input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-control"></div>
        <div class="col-md-1"><a href="{{ route('pelanggaran.index') }}" class="btn btn-light w-100">↺</a></div>
    </form>
    <x-table>
        <x-slot:head><th>No</th><th>Siswa</th><th>Kelas</th><th>Pelanggaran</th><th>Kategori</th><th>Poin</th><th>Tanggal</th><th>Petugas</th><th>Keterangan</th><th class="text-end">Aksi</th></x-slot:head>
        @forelse ($data as $p)
            <tr>
                <td>{{ $data->firstItem() + $loop->index }}</td><td>{{ $p->siswa->nama }}</td><td>{{ $p->siswa->kelas->nama_kelas }}</td>
                <td>{{ $p->jenis->nama }}</td><td>{{ $p->jenis->kategori }}</td><td>{{ $p->poin }}</td>
                <td>{{ $p->tanggal->format('d/m/Y') }}</td><td>{{ $p->petugas->name }}</td><td>{{ Str::limit($p->keterangan, 30) }}</td>
                <td class="text-end text-nowrap">
                    <a href="{{ route('pelanggaran.show', $p) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                    <a href="{{ route('pelanggaran.edit', $p) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                    <button class="btn btn-sm btn-outline-danger" data-delete="{{ route('pelanggaran.destroy', $p) }}"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
        @empty
            <tr><td colspan="10" class="text-center text-muted py-4">Data tidak ditemukan.</td></tr>
        @endforelse
    </x-table>
    <div class="mt-3">{{ $data->links('pagination::bootstrap-5') }}</div>
</x-card>
@endsection
