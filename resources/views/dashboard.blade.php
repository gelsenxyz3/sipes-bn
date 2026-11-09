@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<h4 class="fw-bold mb-1">Dashboard</h4>
<p class="text-muted">Mencatat, Memantau, dan Membina Kedisiplinan Siswa.</p>

@php
    $cards = [
        ['Total Siswa', $totalSiswa, 'bi-people', 'primary'],
        ['Total Kelas', $totalKelas, 'bi-building', 'success'],
        ['Jenis Pelanggaran', $totalJenis, 'bi-list-check', 'info'],
        ['Total Pelanggaran', $totalPelanggaran, 'bi-exclamation-triangle', 'warning'],
        ['Total Poin', $totalPoin, 'bi-bar-chart', 'danger'],
    ];
@endphp
<div class="row g-3 mb-4">
    @foreach ($cards as [$label, $val, $icon, $color])
        <div class="col-6 col-lg">
            <x-card class="mb-0 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div><div class="text-muted small">{{ $label }}</div><div class="fs-3 fw-bold">{{ $val }}</div></div>
                    <div class="fs-1 text-{{ $color }}"><i class="bi {{ $icon }}"></i></div>
                </div>
            </x-card>
        </div>
    @endforeach
</div>

<!-- Bagian Grafik dengan Tinggi Terkontrol Agar Bagian Bawah Terlihat Rapi -->
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <x-card title="Pelanggaran per Bulan ({{ now()->year }})">
            <div style="position: relative; height: 220px; width: 100%;">
                <canvas id="chartBulan"></canvas>
            </div>
        </x-card>
    </div>
    <div class="col-lg-4">
        <x-card title="Berdasarkan Kategori">
            <div style="position: relative; height: 220px; width: 100%; display: flex; justify-content: center; align-items: center;">
                <canvas id="chartKategori"></canvas>
            </div>
        </x-card>
    </div>
</div>

<div class="row g-3">
    <div class="col-xl-8">
        <x-card title="Pelanggaran Terbaru">
            <x-table>
                <x-slot:head><th>Siswa</th><th>Kelas</th><th>Pelanggaran</th><th>Poin</th><th>Tanggal</th><th>Status</th></x-slot:head>
                @forelse ($terbaru as $p)
                    <tr>
                        <td>{{ $p->siswa->nama }}</td>
                        <td>{{ $p->siswa->kelas->nama_kelas }}</td>
                        <td>{{ $p->jenis->nama }}</td>
                        <td>{{ $p->poin }}</td>
                        <td>{{ $p->tanggal->format('d/m/Y') }}</td>
                        <td><x-badge :color="$p->siswa->status[1]">{{ $p->siswa->status[0] }}</x-badge></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Belum ada data.</td></tr>
                @endforelse
            </x-table>
        </x-card>
    </div>
    <div class="col-xl-4">
        <x-card title="Poin Tertinggi">
            @foreach ($teratas as $s)
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <div>
                        <a href="{{ route('siswa.show', $s) }}" class="text-decoration-none fw-semibold">{{ $s->nama }}</a>
                        <div class="small text-muted">{{ $s->kelas->nama_kelas }} · {{ $s->total_poin }} poin</div>
                    </div>
                    <x-badge :color="$s->status[1]">{{ $s->status[0] }}</x-badge>
                </div>
            @endforeach
        </x-card>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // 1. Grafik Batang (Pelanggaran per Bulan)
    new Chart(document.getElementById('chartBulan'), {
        type: 'bar',
        data: {
            labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
            datasets: [{
                label: 'Jumlah',
                data: @json($perBulan),
                backgroundColor: '#0d6efd',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Memastikan grafik mengikuti tinggi container div
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });

    // 2. Grafik Bulat / Doughnut (Berdasarkan Kategori dengan Warna Variatif Otomatis)
    const kategoriLabels = @json($kategori->keys());

    const predefinedColors = [
        '#4e73df', // Biru
        '#1cc88a', // Hijau
        '#36b9cc', // Cyan
        '#f6c23e', // Kuning
        '#e74a3b', // Merah
        '#858796', // Abu-abu
        '#6f42c1', // Ungu
        '#fd7e14'  // Oranye
    ];

    const backgroundColors = kategoriLabels.map((label, index) => {
        const lower = label.toLowerCase();
        if (lower.includes('kedisiplinan')) return '#4e73df';
        if (lower.includes('kerapian')) return '#1cc88a';
        if (lower.includes('kesopanan')) return '#36b9cc';
        if (lower.includes('ketertiban')) return '#f6c23e';
        if (lower.includes('akademik')) return '#e74a3b';
        if (lower.includes('kebersihan')) return '#6f42c1';

        return predefinedColors[index % predefinedColors.length];
    });

    new Chart(document.getElementById('chartKategori'), {
        type: 'doughnut',
        data: {
            labels: kategoriLabels,
            datasets: [{
                data: @json($kategori->values()),
                backgroundColor: backgroundColors,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Memastikan grafik mengikuti tinggi container div
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 12,
                        boxWidth: 8
                    }
                }
            }
        }
    });
</script>
@endpush
