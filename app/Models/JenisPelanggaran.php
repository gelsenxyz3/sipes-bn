<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisPelanggaran extends Model
{
    public const KATEGORI = ['Kedisiplinan', 'Seragam', 'Akademik', 'Berat'];
    public const TINGKAT = ['Ringan', 'Sedang', 'Berat'];

    protected $table = 'jenis_pelanggaran';
    protected $guarded = [];

    public function pelanggaran()
    {
        return $this->hasMany(Pelanggaran::class);
    }
}
