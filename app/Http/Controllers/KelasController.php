<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KelasController extends Controller
{
    // Tambah & edit dilakukan lewat modal di halaman daftar kelas
    public function index()
    {
        $jurusanUrutan = ['RPL' => 1, 'DKV' => 2, 'TKP' => 3, 'TP' => 4, 'KULINER' => 5];
        $tingkatUrutan = ['X' => 1, 'XI' => 2, 'XII' => 3];

        $kelas = Kelas::withCount('siswa')->get()->sort(function ($a, $b) use ($jurusanUrutan, $tingkatUrutan) {
            // Bandingkan Jurusan terlebih dahulu
            $jurusanA = $jurusanUrutan[strtoupper($a->jurusan)] ?? 99;
            $jurusanB = $jurusanUrutan[strtoupper($b->jurusan)] ?? 99;

            if ($jurusanA !== $jurusanB) {
                return $jurusanA <=> $jurusanB;
            }

            // Jika jurusan sama, bandingkan tingkat (X -> XI -> XII)
            $tingkatA = $tingkatUrutan[$a->tingkat] ?? 99;
            $tingkatB = $tingkatUrutan[$b->tingkat] ?? 99;

            if ($tingkatA !== $tingkatB) {
                return $tingkatA <=> $tingkatB;
            }

            // Terakhir urutkan nama kelas
            return strcmp($a->nama_kelas, $b->nama_kelas);
        });

        return view('kelas.index', compact('kelas'));
    }
    private function rules(?Kelas $k = null): array
    {
        return [
            'nama_kelas' => ['required', 'max:50', Rule::unique('kelas', 'nama_kelas')->ignore($k?->id)],
            'jurusan' => 'required|max:50',
            'tingkat' => 'required|in:X,XI,XII',
            'wali_kelas' => 'required|max:100',
        ];
    }

    public function store(Request $request)
    {
        Kelas::create($request->validate($this->rules()));
        return back()->with('success', 'Data berhasil ditambahkan');
    }

    public function show(Kelas $kelas)
    {
        return view('kelas.show', ['kelas' => $kelas, 'siswas' => $kelas->siswa()->orderBy('nama')->get()]);
    }

    public function update(Request $request, Kelas $kelas)
    {
        $kelas->update($request->validate($this->rules($kelas)));
        return back()->with('success', 'Data berhasil diperbarui');
    }

    public function destroy(Kelas $kelas)
    {
        if ($kelas->siswa()->exists()) {
            return back()->with('error', 'Kelas masih memiliki siswa, tidak dapat dihapus.');
        }
        $kelas->delete();
        return back()->with('success', 'Data berhasil dihapus');
    }
}
