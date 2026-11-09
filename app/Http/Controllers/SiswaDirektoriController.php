<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Pelanggaran;
use Illuminate\Http\Request;

class SiswaDirektoriController extends Controller
{
    public function index(Request $request)
    {
        // Query Utama Pelanggaran dengan relasi
        $query = Pelanggaran::with(['siswa.kelas', 'jenis']);

        // 1. Pencarian (Nama, NIS, atau NISN Siswa)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('siswa', function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        // 2. Filter berdasarkan Kelas
        if ($request->filled('kelas_id')) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }

        // 3. Filter berdasarkan Kategori (Ringan, Sedang, Berat)
        if ($request->filled('kategori')) {
            $query->whereHas('jenis', function($q) use ($request) {
                $q->where('kategori', $request->kategori);
            });
        }

        // 4. Filter berdasarkan Jenis Kelamin
        if ($request->filled('jenis_kelamin')) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('jenis_kelamin', $request->jenis_kelamin);
            });
        }

        // Pengurutan Kategori berjenjang (Ringan, Sedang, Berat) menggunakan foreign key jenis_pelanggaran_id
        $query->orderByRaw("
            FIELD(
                (SELECT kategori FROM jenis_pelanggaran WHERE jenis_pelanggaran.id = pelanggaran.jenis_pelanggaran_id LIMIT 1),
                'Ringan', 'Sedang', 'Berat'
            ) ASC
        ");

        // Pagination Dinamis
        $perPage = $request->get('per_page', 10);
        $pelanggarans = $query->paginate($perPage)->withQueryString();

        // Data pendukung untuk dropdown filter kelas
        $kelasList = Kelas::all();

        return view('siswa.direktori-index', compact(
            'pelanggarans',
            'kelasList'
        ));
    }

    public function show($id)
    {
        $siswa = Siswa::with(['kelas', 'pelanggaran.jenis', 'pelanggaran.petugas'])->findOrFail($id);
        return view('siswa.direktori-show', compact('siswa'));
    }
}
