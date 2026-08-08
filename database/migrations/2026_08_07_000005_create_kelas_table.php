<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// F10: Admin mengatur kelas, penawaran kelas per periode (kuota max/min,
// jadwal, guru). Business Rule #6: satu guru tidak boleh dua kelas
// dengan jadwal bentrok di periode yang sama (divalidasi di service layer,
// bukan di level migration).
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->restrictOnDelete();
            $table->foreignId('guru_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('periode_id')->constrained('periode_pendaftaran')->restrictOnDelete();

            $table->unsignedInteger('kuota_maksimum');
            $table->unsignedInteger('kuota_minimum');
            $table->unsignedInteger('kuota_terisi')->default(0); // dikunci saat pendaftaran diajukan

            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');

            $table->decimal('biaya_periode', 12, 2); // total biaya satu periode
            $table->decimal('biaya_dp', 12, 2)->nullable(); // biaya reservasi (non-refundable)

            $table->enum('status', ['aktif', 'dibatalkan'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
