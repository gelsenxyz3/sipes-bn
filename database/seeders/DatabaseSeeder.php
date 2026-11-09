<?php

namespace Database\Seeders;

use App\Models\JenisPelanggaran;
use App\Models\Kelas;
use App\Models\Pelanggaran;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@sipes.test',
            'password' => Hash::make('password'),
        ]);

        $kelas = collect([
            ['X RPL 1', 'RPL', 'X', 'Budi Santoso, S.Pd'],
            ['X RPL 2', 'RPL', 'X', 'Siti Aminah, S.Kom'],
            ['XI RPL 1', 'RPL', 'XI', 'Agus Wijaya, S.T'],
            ['XII RPL 1', 'RPL', 'XII', 'Dewi Lestari, S.Pd'],
        ])->map(fn ($k) => Kelas::create([
            'nama_kelas' => $k[0], 'jurusan' => $k[1], 'tingkat' => $k[2], 'wali_kelas' => $k[3],
        ]));

        $nama = ['Ahmad Fauzi', 'Rina Marlina', 'Dimas Prasetyo', 'Putri Ayu', 'Rizky Ramadhan', 'Nadia Safitri',
            'Fajar Nugroho', 'Salsabila Putri', 'Bayu Setiawan', 'Citra Dewi', 'Eko Prabowo', 'Laila Hidayah'];
        $siswa = collect($nama)->map(fn ($n, $i) => Siswa::create([
            'kelas_id' => $kelas[$i % 4]->id,
            'nis' => '2024'.str_pad($i + 1, 4, '0', STR_PAD_LEFT),
            'nisn' => '00'.(51234560 + $i),
            'nama' => $n,
            'jenis_kelamin' => $i % 2 === 0 ? 'L' : 'P',
            'tempat_lahir' => 'Semarang',
            'tanggal_lahir' => now()->subYears(16)->subDays($i * 37)->toDateString(),
            'alamat' => 'Jl. Merdeka No. '.($i + 1).', Semarang',
            'telepon' => '08123456'.str_pad($i, 4, '0', STR_PAD_LEFT),
            'nama_wali' => 'Bapak/Ibu '.explode(' ', $n)[1],
        ]));

        $jenis = collect([
            ['Terlambat masuk sekolah', 'Kedisiplinan', 5, 'Ringan'],
            ['Tidak menggunakan atribut lengkap', 'Seragam', 10, 'Ringan'],
            ['Tidak mengerjakan tugas', 'Akademik', 5, 'Ringan'],
            ['Membolos', 'Kedisiplinan', 20, 'Sedang'],
            ['Merokok di lingkungan sekolah', 'Berat', 50, 'Berat'],
            ['Seragam tidak sesuai ketentuan', 'Seragam', 10, 'Ringan'],
            ['Membawa HP saat ujian', 'Akademik', 25, 'Sedang'],
            ['Berkelahi', 'Berat', 60, 'Berat'],
            ['Rambut tidak rapi', 'Seragam', 5, 'Ringan'],
            ['Keluar kelas tanpa izin', 'Kedisiplinan', 10, 'Sedang'],
        ])->map(fn ($j) => JenisPelanggaran::create([
            'nama' => $j[0], 'kategori' => $j[1], 'poin' => $j[2], 'tingkat' => $j[3],
            'keterangan' => 'Sesuai tata tertib sekolah.',
        ]));

        for ($i = 0; $i < 20; $i++) {
            $s = $siswa[($i * 5) % 12];
            $j = $jenis[($i * 3) % 10];
            Pelanggaran::create([
                'siswa_id' => $s->id,
                'jenis_pelanggaran_id' => $j->id,
                'user_id' => $admin->id,
                'tanggal' => now()->subDays($i * 9)->toDateString(),
                'waktu' => sprintf('%02d:%02d', 7 + $i % 6, ($i * 7) % 60),
                'poin' => $j->poin,
                'keterangan' => 'Data contoh pelanggaran.',
            ]);
            $s->increment('total_poin', $j->poin);
        }
    }
}
