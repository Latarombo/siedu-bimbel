<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// F12: Guru mencatat kehadiran siswa tiap pertemuan.
// Terikat ke `pendaftaran`, bukan langsung ke siswa, supaya presensi tetap
// terpisah per mata pelajaran/kelas kalau siswa ikut lebih dari satu (Business Rule #8).
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftaran')->cascadeOnDelete();
            $table->foreignId('dicatat_oleh')->constrained('users')->restrictOnDelete(); // guru

            $table->date('tanggal_pertemuan');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpa']);
            $table->text('catatan')->nullable();

            $table->timestamps();

            $table->unique(['pendaftaran_id', 'tanggal_pertemuan']); // 1 presensi per pertemuan
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensi');
    }
};
