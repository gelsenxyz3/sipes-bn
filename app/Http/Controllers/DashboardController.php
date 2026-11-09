<?php

namespace App\Http\Controllers;

use App\Models\JenisPelanggaran;
use App\Models\Kelas;
use App\Models\Pelanggaran;
use App\Models\Siswa;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $bulan = Pelanggaran::selectRaw('MONTH(tanggal) as m, COUNT(*) as t')
            ->whereYear('tanggal', now()->year)->groupBy('m')->pluck('t', 'm');

        $kategori = Pelanggaran::join('jenis_pelanggaran as j', 'j.id', '=', 'pelanggaran.jenis_pelanggaran_id')
            ->selectRaw('j.kategori as k, COUNT(*) as t')->groupBy('k')->pluck('t', 'k');

        return view('dashboard', [
            'totalSiswa' => Siswa::count(),
            'totalKelas' => Kelas::count(),
            'totalJenis' => JenisPelanggaran::count(),
            'totalPelanggaran' => Pelanggaran::count(),
            'totalPoin' => Pelanggaran::sum('poin'),
            'perBulan' => collect(range(1, 12))->map(fn ($m) => $bulan[$m] ?? 0)->all(),
            'kategori' => $kategori,
            'terbaru' => Pelanggaran::with(['siswa.kelas', 'jenis'])->latest('tanggal')->latest('id')->take(8)->get(),
            'teratas' => Siswa::with('kelas')->orderByDesc('total_poin')->take(5)->get(),
        ]);
    }
}
