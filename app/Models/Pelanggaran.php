<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggaran extends Model
{
    protected $table = 'pelanggaran';
    protected $guarded = [];
    protected $casts = ['tanggal' => 'date'];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function jenis()
    {
        return $this->belongsTo(JenisPelanggaran::class, 'jenis_pelanggaran_id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
