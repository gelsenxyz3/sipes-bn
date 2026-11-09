@extends('layouts.app')
@section('title', 'Detail Pelanggaran')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0">Detail Pelanggaran</h4>
    <a href="{{ route('pelanggaran.index') }}" class="btn btn-light">← Kembali</a>
</div>
<x-card>
    <table class="table mb-0">
        <tr><th style="width:200px">Siswa</th><td><a href="{{ route('siswa.show', $p->siswa) }}">{{ $p->siswa->nama }}</a> ({{ $p->siswa->kelas->nama_kelas }})</td></tr>
        <tr><th>Pelanggaran</th><td>{{ $p->jenis->nama }}</td></tr>
        <tr><th>Kategori / Tingkat</th><td>{{ $p->jenis->kategori }} / {{ $p->jenis->tingkat }}</td></tr>
        <tr><th>Poin</th><td>{{ $p->poin }}</td></tr>
        <tr><th>Waktu Kejadian</th><td>{{ $p->tanggal->format('d/m/Y') }} {{ substr($p->waktu, 0, 5) }}</td></tr>
        <tr><th>Petugas</th><td>{{ $p->petugas->name }}</td></tr>
        <tr><th>Keterangan</th><td>{{ $p->keterangan ?: '-' }}</td></tr>
        <tr><th>Bukti</th><td>@if ($p->bukti)<img src="{{ asset('storage/'.$p->bukti) }}" class="img-fluid rounded" style="max-height:300px">@else - @endif</td></tr>
    </table>
</x-card>
@endsection
