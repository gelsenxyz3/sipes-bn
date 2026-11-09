<?php

namespace App\Http\Controllers;

use App\Models\JenisPelanggaran;
use Illuminate\Http\Request;

class JenisPelanggaranController extends Controller
{
    /**
     * Aturan penentuan tingkat otomatis berdasarkan jumlah poin.
     * Dapat disesuaikan dengan mudah di sini.
     */
    private function hitungTingkat(int $poin): string
    {
        if ($poin <= 10) {
            return 'Ringan';
        } elseif ($poin <= 25) {
            return 'Sedang';
        } else {
            return 'Berat';
        }
    }

    private function rules(): array
    {
        return [
            'nama' => 'required|max:150',
            'kategori' => 'required|in:Kedisiplinan,Kerapian,Kesopanan dan Etika,Ketertiban,Akademik,Kebersihan',
            'poin' => 'required|integer|min:1|max:200',
            'keterangan' => 'nullable|max:500',
        ];
    }

    public function index()
    {
        return view('jenis.index', [
            'jenis' => JenisPelanggaran::orderBy('kategori')->orderBy('nama')->get()
        ]);
    }

    public function create()
    {
        return view('jenis.form', ['jenis' => new JenisPelanggaran]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        // Tentukan tingkat secara otomatis berdasarkan poin
        $data['tingkat'] = $this->hitungTingkat($data['poin']);

        JenisPelanggaran::create($data);

        return redirect()->route('jenis-pelanggaran.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit(JenisPelanggaran $jenis)
    {
        return view('jenis.form', compact('jenis'));
    }

    public function update(Request $request, JenisPelanggaran $jenis)
    {
        $data = $request->validate($this->rules());

        // Tentukan tingkat secara otomatis berdasarkan poin
        $data['tingkat'] = $this->hitungTingkat($data['poin']);

        $jenis->update($data);

        return redirect()->route('jenis-pelanggaran.index')->with('success', 'Data berhasil diperbarui');
    }

    public function destroy(JenisPelanggaran $jenis)
    {
        if ($jenis->pelanggaran()->exists()) {
            return back()->with('error', 'Jenis ini sudah dipakai pada data pelanggaran, tidak dapat dihapus.');
        }

        $jenis->delete();

        return back()->with('success', 'Data berhasil dihapus');
    }
}
