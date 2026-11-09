<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SiswaController extends Controller
{
    private function rules(?Siswa $s = null): array
    {
        return [
            'kelas_id' => 'required|exists:kelas,id',
            'nis' => ['required', 'max:20', Rule::unique('siswa', 'nis')->ignore($s?->id)],
            'nisn' => ['required', 'max:20', Rule::unique('siswa', 'nisn')->ignore($s?->id)],
            'nama' => 'required|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|max:60',
            'tanggal_lahir' => 'required|date|before:today',
            'alamat' => 'required',
            'telepon' => 'nullable|max:20',
            'nama_wali' => 'required|max:100',
        ];
    }

    public function index(Request $request)
    {
        $siswas = Siswa::with('kelas')
            ->when($request->q, fn ($q, $v) => $q->where(fn ($w) => $w
                ->where('nama', 'like', "%$v%")->orWhere('nis', 'like', "%$v%")->orWhere('nisn', 'like', "%$v%")))
            ->when($request->kelas_id, fn ($q, $v) => $q->where('kelas_id', $v))
            ->orderBy('nama')->paginate(10)->withQueryString();

        return view('siswa.index', ['siswas' => $siswas, 'kelas' => Kelas::orderBy('nama_kelas')->get()]);
    }

    public function create()
    {
        return view('siswa.form', ['siswa' => new Siswa, 'kelas' => Kelas::orderBy('nama_kelas')->get()]);
    }

    public function store(Request $request)
    {
        Siswa::create($request->validate($this->rules()));
        return redirect()->route('siswa.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function show(Siswa $siswa)
    {
        $siswa->load('kelas');
        $riwayat = $siswa->pelanggaran()->with('jenis')->latest('tanggal')->latest('id')->get();
        return view('siswa.show', compact('siswa', 'riwayat'));
    }

    public function edit(Siswa $siswa)
    {
        return view('siswa.form', ['siswa' => $siswa, 'kelas' => Kelas::orderBy('nama_kelas')->get()]);
    }

    public function update(Request $request, Siswa $siswa)
    {
        $siswa->update($request->validate($this->rules($siswa)));
        return redirect()->route('siswa.index')->with('success', 'Data berhasil diperbarui');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Data berhasil dihapus');
    }

    /**
     * Tambahan fungsi untuk Live Search nama siswa pada form pencatatan pelanggaran.
     */
    public function search(Request $request)
    {
        $keyword = $request->get('q');

        $siswa = Siswa::with('kelas')
                    ->where('nama', 'LIKE', "%{$keyword}%")
                    ->orWhere('nis', 'LIKE', "%{$keyword}%")
                    ->limit(10)
                    ->get();

        return response()->json($siswa);
    }
}
