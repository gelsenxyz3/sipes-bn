<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Pelanggaran;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $r)
    {
        $periode = $r->input('periode', 'bulanan');
        $t = Carbon::parse($r->input('tanggal', today()));
        [$a, $b] = match ($periode) {
            'harian' => [$t->copy()->startOfDay(), $t->copy()->endOfDay()],
            'mingguan' => [$t->copy()->startOfWeek(), $t->copy()->endOfWeek()],
            'tahunan' => [$t->copy()->startOfYear(), $t->copy()->endOfYear()],
            default => [$t->copy()->startOfMonth(), $t->copy()->endOfMonth()],
        };

        $rows = Pelanggaran::with(['siswa.kelas', 'jenis', 'petugas'])
            ->whereBetween('tanggal', [$a->toDateString(), $b->toDateString()])
            ->when($r->siswa_id, fn ($x, $v) => $x->where('siswa_id', $v))
            ->when($r->kelas_id, fn ($x, $v) => $x->whereHas('siswa', fn ($s) => $s->where('kelas_id', $v)))
            ->when($r->kategori, fn ($x, $v) => $x->whereHas('jenis', fn ($j) => $j->where('kategori', $v)))
            ->orderBy('tanggal')->get();

        if ($r->has('export')) {
            return response()->streamDownload(function () use ($rows) {
                $f = fopen('php://output', 'w');
                fwrite($f, "\xEF\xBB\xBF");
                fputcsv($f, ['No', 'Tanggal', 'NIS', 'Nama', 'Kelas', 'Pelanggaran', 'Kategori', 'Poin', 'Petugas', 'Keterangan'], ';');
                foreach ($rows as $i => $p) {
                    fputcsv($f, [$i + 1, $p->tanggal->format('d/m/Y'), $p->siswa->nis, $p->siswa->nama,
                        $p->siswa->kelas->nama_kelas, $p->jenis->nama, $p->jenis->kategori, $p->poin,
                        $p->petugas->name, $p->keterangan], ';');
                }
                fclose($f);
            }, 'laporan-pelanggaran.csv', ['Content-Type' => 'text/csv']);
        }

        return view('laporan', [
            'rows' => $rows, 'a' => $a, 'b' => $b, 'periode' => $periode, 'tanggal' => $t->toDateString(),
            'kelas' => Kelas::orderBy('nama_kelas')->get(), 'siswas' => Siswa::orderBy('nama')->get(),
        ]);
    }
}
