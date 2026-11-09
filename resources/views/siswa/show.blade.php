@extends('layouts.app')
@section('title', 'Detail Siswa')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0">Detail Siswa</h4>
    <a href="{{ route('siswa.index') }}" class="btn btn-light">← Kembali</a>
</div>
<div class="row">
    <div class="col-lg-4">
        <x-card class="text-center">
            <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center fs-1 fw-bold mb-3" style="width:96px;height:96px">{{ strtoupper(substr($siswa->nama, 0, 1)) }}</div>
            <h5 class="fw-bold">{{ $siswa->nama }}</h5>
            <x-badge :color="$siswa->status[1]">{{ $siswa->status[0] }}</x-badge>
            <table class="table table-sm text-start mt-3 mb-0">
                <tr><th>NIS</th><td>{{ $siswa->nis }}</td></tr>
                <tr><th>NISN</th><td>{{ $siswa->nisn }}</td></tr>
                <tr><th>Kelas</th><td>{{ $siswa->kelas->nama_kelas }}</td></tr>
                <tr><th>Jurusan</th><td>{{ $siswa->kelas->jurusan }}</td></tr>
                <tr><th>Jenis Kelamin</th><td>{{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                <tr><th>TTL</th><td>{{ $siswa->tempat_lahir }}, {{ $siswa->tanggal_lahir->format('d/m/Y') }}</td></tr>
                <tr><th>Wali</th><td>{{ $siswa->nama_wali }}</td></tr>
                <tr><th>Alamat</th><td>{{ $siswa->alamat }}</td></tr>
            </table>
        </x-card>
    </div>
    <div class="col-lg-8">
        <div class="row g-3 mb-4">
            <div class="col-md-4"><x-card class="mb-0"><div class="text-muted small">Total Pelanggaran</div><div class="fs-3 fw-bold">{{ $riwayat->count() }}</div></x-card></div>
            <div class="col-md-4"><x-card class="mb-0"><div class="text-muted small">Total Poin</div><div class="fs-3 fw-bold">{{ $siswa->total_poin }}</div></x-card></div>
            <div class="col-md-4"><x-card class="mb-0"><div class="text-muted small">Pelanggaran Terakhir</div><div class="fw-bold">{{ $riwayat->first()?->tanggal->format('d/m/Y') ?? '-' }}</div></x-card></div>
        </div>
        <x-card title="Riwayat Pelanggaran">
            <x-table>
                <x-slot:head><th>Tanggal</th><th>Pelanggaran</th><th>Kategori</th><th>Poin</th><th>Keterangan</th></x-slot:head>
                @forelse ($riwayat as $p)
                    <tr><td>{{ $p->tanggal->format('d/m/Y') }}</td><td>{{ $p->jenis->nama }}</td><td>{{ $p->jenis->kategori }}</td><td>{{ $p->poin }}</td><td>{{ $p->keterangan }}</td></tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">Belum ada pelanggaran.</td></tr>
                @endforelse
            </x-table>
        </x-card>
    </div>
</div>
@endsection
