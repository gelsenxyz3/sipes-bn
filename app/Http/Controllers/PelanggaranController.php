<?php

namespace App\Http\Controllers;

use App\Models\JenisPelanggaran;
use App\Models\Kelas;
use App\Models\Pelanggaran;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PelanggaranController extends Controller
{
    private function rules(): array
    {
        return [
            'siswa_id' => 'required|exists:siswa,id',
            'jenis_pelanggaran_id' => 'required|exists:jenis_pelanggaran,id',
            'tanggal' => 'required|date|before_or_equal:today',
            'waktu' => 'required|date_format:H:i',
            'keterangan' => 'nullable|max:500',
            'bukti' => 'nullable|image|max:2048',
        ];
    }

    private function formData(Pelanggaran $p): array
    {
        return [
            'pelanggaran' => $p,
            'kelas' => Kelas::orderBy('nama_kelas')->get(),
            'siswas' => Siswa::with('kelas')->orderBy('nama')->get(),
            'jenis' => JenisPelanggaran::orderBy('nama')->get(),
        ];
    }

    public function index(Request $r)
    {
        $data = Pelanggaran::with(['siswa.kelas', 'jenis', 'petugas'])
            ->when($r->q, fn ($x, $v) => $x->whereHas('siswa', fn ($s) => $s->where('nama', 'like', "%$v%")))
            ->when($r->siswa_id, fn ($x, $v) => $x->where('siswa_id', $v))
            ->when($r->kelas_id, fn ($x, $v) => $x->whereHas('siswa', fn ($s) => $s->where('kelas_id', $v)))
            ->when($r->kategori, fn ($x, $v) => $x->whereHas('jenis', fn ($j) => $j->where('kategori', $v)))
            ->when($r->tanggal, fn ($x, $v) => $x->whereDate('tanggal', $v))
            ->latest('tanggal')->latest('id')->paginate(10)->withQueryString();

        return view('pelanggaran.index', [
            'data' => $data,
            'kelas' => Kelas::orderBy('nama_kelas')->get(),
            'siswas' => Siswa::orderBy('nama')->get(),
        ]);
    }

    public function create()
    {
        return view('pelanggaran.form', $this->formData(new Pelanggaran));
    }

    public function store(Request $request)
    {
        $d = $request->validate($this->rules());

        DB::transaction(function () use ($request, $d) {
            $jenis = JenisPelanggaran::findOrFail($d['jenis_pelanggaran_id']);
            if ($request->hasFile('bukti')) {
                $d['bukti'] = $request->file('bukti')->store('bukti', 'public');
            }
            $d['poin'] = $jenis->poin;
            $d['user_id'] = Auth::id();
            Pelanggaran::create($d);
            Siswa::findOrFail($d['siswa_id'])->increment('total_poin', $jenis->poin);
        });

        return redirect()->route('pelanggaran.index')->with('success', 'Pelanggaran berhasil dicatat');
    }

    public function show(Pelanggaran $pelanggaran)
    {
        $pelanggaran->load(['siswa.kelas', 'jenis', 'petugas']);
        return view('pelanggaran.show', ['p' => $pelanggaran]);
    }

    public function edit(Pelanggaran $pelanggaran)
    {
        return view('pelanggaran.form', $this->formData($pelanggaran));
    }

    public function update(Request $request, Pelanggaran $pelanggaran)
    {
        $d = $request->validate($this->rules());

        DB::transaction(function () use ($request, $d, $pelanggaran) {
            // kembalikan poin lama, lalu tambahkan poin baru
            Siswa::findOrFail($pelanggaran->siswa_id)->decrement('total_poin', $pelanggaran->poin);
            $jenis = JenisPelanggaran::findOrFail($d['jenis_pelanggaran_id']);
            if ($request->hasFile('bukti')) {
                if ($pelanggaran->bukti) {
                    Storage::disk('public')->delete($pelanggaran->bukti);
                }
                $d['bukti'] = $request->file('bukti')->store('bukti', 'public');
            }
            $d['poin'] = $jenis->poin;
            $pelanggaran->update($d);
            Siswa::findOrFail($d['siswa_id'])->increment('total_poin', $jenis->poin);
        });

        return redirect()->route('pelanggaran.index')->with('success', 'Data berhasil diperbarui');
    }

    public function destroy(Pelanggaran $pelanggaran)
    {
        DB::transaction(function () use ($pelanggaran) {
            Siswa::findOrFail($pelanggaran->siswa_id)->decrement('total_poin', $pelanggaran->poin);
            if ($pelanggaran->bukti) {
                Storage::disk('public')->delete($pelanggaran->bukti);
            }
            $pelanggaran->delete();
        });

        return redirect()->route('pelanggaran.index')->with('success', 'Data berhasil dihapus');
    }
}
