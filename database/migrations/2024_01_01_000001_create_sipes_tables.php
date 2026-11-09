<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $t) {
            $t->id();
            $t->string('nama_kelas')->unique();
            $t->string('jurusan');
            $t->string('tingkat', 5);
            $t->string('wali_kelas');
            $t->timestamps();
        });

        Schema::create('siswa', function (Blueprint $t) {
            $t->id();
            $t->foreignId('kelas_id')->constrained('kelas')->restrictOnDelete();
            $t->string('nis')->unique();
            $t->string('nisn')->unique();
            $t->string('nama');
            $t->enum('jenis_kelamin', ['L', 'P']);
            $t->string('tempat_lahir');
            $t->date('tanggal_lahir');
            $t->text('alamat');
            $t->string('telepon')->nullable();
            $t->string('nama_wali');
            $t->unsignedInteger('total_poin')->default(0);
            $t->timestamps();
        });

        Schema::create('jenis_pelanggaran', function (Blueprint $t) {
            $t->id();
            $t->string('nama');
            $t->string('kategori');
            $t->unsignedInteger('poin');
            $t->string('tingkat');
            $t->text('keterangan')->nullable();
            $t->timestamps();
        });

        Schema::create('pelanggaran', function (Blueprint $t) {
            $t->id();
            $t->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $t->foreignId('jenis_pelanggaran_id')->constrained('jenis_pelanggaran')->restrictOnDelete();
            $t->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $t->date('tanggal');
            $t->time('waktu');
            $t->unsignedInteger('poin');
            $t->text('keterangan')->nullable();
            $t->string('bukti')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggaran');
        Schema::dropIfExists('jenis_pelanggaran');
        Schema::dropIfExists('siswa');
        Schema::dropIfExists('kelas');
    }
};
