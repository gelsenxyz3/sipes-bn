@extends('layouts.app')
@section('title', 'Laporan Pelanggaran')
@section('content')
<h4 class="fw-bold mb-3 no-print">Laporan Pelanggaran</h4>
<x-card class="no-print">
    <form class="row g-2" method="GET">
        <div class="col-md-2"><select name="periode" class="form-select">
            @foreach (['harian' => 'Hari', 'mingguan' => 'Minggu', 'bulanan' => 'Bulan', 'tahunan' => 'Tahun'] as $k => $v)<option value="{{ $k }}" @selected($periode == $k)>{{ $v }}</option>@endforeach</select></div>
        <div class="col-md-2"><input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control"></div>
        <div class="col-md-2"><select name="kelas_id" class="form-select"><option value="">Semua Kelas</option>
            @foreach ($kelas as $k)<option value="{{ $k->id }}" @selected(request('kelas_id') == $k->id)>{{ $k->nama_kelas }}</option>@endforeach</select></div>
        <div class="col-md-2"><select name="siswa_id" class="form-select"><option value="">Semua Siswa</option>
            @foreach ($siswas as $s)<option value="{{ $s->id }}" @selected(request('siswa_id') == $s->id)>{{ $s->nama }}</option>@endforeach</select></div>
        <div class="col-md-2"><select name="kategori" class="form-select"><option value="">Semua Kategori</option>
            @foreach (App\Models\JenisPelanggaran::KATEGORI as $k)<option @selected(request('kategori') == $k)>{{ $k }}</option>@endforeach</select></div>
        <div class="col-md-2 d-grid"><x-button>Tampilkan</x-button></div>
    </form>
    <div class="mt-3 d-flex gap-2 flex-wrap">
        <button class="btn btn-outline-secondary" onclick="window.print()"><i class="bi bi-printer"></i> Cetak / Simpan PDF</button>
        <a class="btn btn-outline-success" href="{{ request()->fullUrlWithQuery(['export' => 1]) }}"><i class="bi bi-file-earmark-excel"></i> Export Excel (CSV)</a>
    </div>
</x-card>

<x-card>
    <div class="text-center mb-3">
        <h5 class="fw-bold mb-0">LAPORAN PELANGGARAN SISWA</h5>
        <div>Periode: {{ $a->format('d/m/Y') }} s.d. {{ $b->format('d/m/Y') }}</div>
    </div>
    <x-table>
        <x-slot:head><th>No</th><th>Tanggal</th><th>NIS</th><th>Nama</th><th>Kelas</th><th>Pelanggaran</th><th>Kategori</th><th>Poin</th><th>Petugas</th></x-slot:head>
        @forelse ($rows as $p)
            <tr><td>{{ $loop->iteration }}</td><td>{{ $p->tanggal->format('d/m/Y') }}</td><td>{{ $p->siswa->nis }}</td><td>{{ $p->siswa->nama }}</td>
                <td>{{ $p->siswa->kelas->nama_kelas }}</td><td>{{ $p->jenis->nama }}</td><td>{{ $p->jenis->kategori }}</td><td>{{ $p->poin }}</td><td>{{ $p->petugas->name }}</td></tr>
        @empty
            <tr><td colspan="9" class="text-center text-muted">Tidak ada data pada periode ini.</td></tr>
        @endforelse
    </x-table>
    <div class="mt-3 fw-semibold">Total kasus: {{ $rows->count() }} · Total poin: {{ $rows->sum('poin') }}</div>
</x-card>
@endsection
