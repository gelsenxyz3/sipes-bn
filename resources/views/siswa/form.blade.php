@extends('layouts.app')
@section('title', $siswa->exists ? 'Edit Siswa' : 'Tambah Siswa')
@section('content')
<h4 class="fw-bold mb-3">{{ $siswa->exists ? 'Edit Siswa' : 'Tambah Siswa' }}</h4>
<x-card>
    <form method="POST" action="{{ $siswa->exists ? route('siswa.update', $siswa) : route('siswa.store') }}">
        @csrf
        @if ($siswa->exists) @method('PUT') @endif
        <div class="row">
            <div class="col-md-6"><x-form-input name="nis" label="NIS" :value="$siswa->nis" /></div>
            <div class="col-md-6"><x-form-input name="nisn" label="NISN" :value="$siswa->nisn" /></div>
            <div class="col-md-6"><x-form-input name="nama" label="Nama Lengkap" :value="$siswa->nama" /></div>
            <div class="col-md-6"><x-form-select name="jenis_kelamin" label="Jenis Kelamin" :options="['L' => 'Laki-laki', 'P' => 'Perempuan']" :value="$siswa->jenis_kelamin" /></div>
            <div class="col-md-6"><x-form-input name="tempat_lahir" label="Tempat Lahir" :value="$siswa->tempat_lahir" /></div>
            <div class="col-md-6"><x-form-input name="tanggal_lahir" label="Tanggal Lahir" type="date" :value="$siswa->tanggal_lahir?->format('Y-m-d')" /></div>
            <div class="col-md-6"><x-form-select name="kelas_id" label="Kelas" :options="$kelas->pluck('nama_kelas', 'id')" :value="$siswa->kelas_id" /></div>
            <div class="col-md-6"><x-form-input name="telepon" label="Nomor Telepon" :value="$siswa->telepon" /></div>
            <div class="col-md-12"><x-form-input name="nama_wali" label="Nama Orang Tua/Wali" :value="$siswa->nama_wali" /></div>

            <!-- Pilihan Alamat / Asrama -->
            <div class="col-12 mb-3">
                <x-form-select
                    name="alamat"
                    label="Alamat / Asrama"
                    :options="[
                        'Wisma Remaja Putra SMK Bagimu Negeriku' => 'Wisma Remaja Putra SMK Bagimu Negeriku',
                        'Wisma Remaja Putri SMK Bagimu Negeriku' => 'Wisma Remaja Putri SMK Bagimu Negeriku'
                    ]"
                    :value="$siswa->alamat"
                />
            </div>
        </div>
        <x-button>Simpan</x-button>
        <a href="{{ route('siswa.index') }}" class="btn btn-light">Batal</a>
    </form>
</x-card>
@endsection
