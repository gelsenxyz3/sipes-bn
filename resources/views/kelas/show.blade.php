@extends('layouts.app')
@section('title', 'Siswa Kelas '.$kelas->nama_kelas)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0">Siswa Kelas {{ $kelas->nama_kelas }}</h4>
    <a href="{{ route('kelas.index') }}" class="btn btn-light">← Kembali</a>
</div>
<x-card>
    <p class="text-muted">Wali kelas: {{ $kelas->wali_kelas }} · Jurusan: {{ $kelas->jurusan }}</p>
    <x-table>
        <x-slot:head><th>No</th><th>NIS</th><th>Nama</th><th>Poin</th><th>Status</th></x-slot:head>
        @forelse ($siswas as $s)
            <tr><td>{{ $loop->iteration }}</td><td>{{ $s->nis }}</td>
                <td><a href="{{ route('siswa.show', $s) }}" class="text-decoration-none">{{ $s->nama }}</a></td>
                <td>{{ $s->total_poin }}</td><td><x-badge :color="$s->status[1]">{{ $s->status[0] }}</x-badge></td></tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted">Belum ada siswa di kelas ini.</td></tr>
        @endforelse
    </x-table>
</x-card>
@endsection
