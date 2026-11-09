<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';
    protected $guarded = [];
    protected $casts = ['tanggal_lahir' => 'date'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    // Relasi bentuk tunggal (sesuai kode lama Anda)
    public function pelanggaran()
    {
        return $this->hasMany(Pelanggaran::class);
    }

    // Alias bentuk jamak untuk memudahkan pemanggilan di Direktori 360°
    public function pelanggarans()
    {
        return $this->hasMany(Pelanggaran::class, 'siswa_id');
    }

    // Menghitung total poin secara dinamis dari relasi pelanggaran
    public function getTotalPoinAttribute()
    {
        // Jika di tabel siswa ada kolom total_poin, ganti baris ini dengan: return $this->attributes['total_poin'] ?? 0;
        return $this->pelanggaran()->sum('poin');
    }

    // Mengembalikan [label, warna bootstrap] berdasarkan total poin
    public function getStatusAttribute(): array
    {
        $poin = $this->total_poin;

        return match (true) {
            $poin <= 20 => ['Aman', 'success'],
            $poin <= 40 => ['Perlu Perhatian', 'info'],
            $poin <= 60 => ['Peringatan', 'warning'],
            $poin <= 100 => ['Pelanggaran Berat', 'danger'],
            default => ['Sangat Berat', 'dark'],
        };
    }
}
