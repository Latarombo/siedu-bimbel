<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// F14: siswa/wali mengajukan pembatalan; admin memverifikasi kasus khusus
// yang minta refund di luar aturan default (tanpa refund).
// Pembatalan otomatis (timeout / tunggakan) TIDAK lewat tabel ini — itu
// cukup ubah kolom `status` di tabel pendaftaran langsung, karena tidak
// ada pengajuan manual dari siswa.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_pembatalan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftaran')->cascadeOnDelete();
            $table->foreignId('diajukan_oleh')->constrained('users')->restrictOnDelete(); // siswa/wali

            $table->text('alasan');
            $table->boolean('minta_refund_khusus')->default(false); // klaim kesalahan sistem/pembayaran

            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->foreignId('diproses_oleh')->nullable()->constrained('users')->nullOnDelete(); // admin
            $table->text('catatan_admin')->nullable();
            $table->timestamp('diproses_pada')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_pembatalan');
    }
};
