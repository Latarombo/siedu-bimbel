<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// F4, F5, F7 & Business Rule #8: satu Pendaftaran = satu siswa + satu kelas
// + satu periode. Kategori usia & jenjang di-snapshot di sini (bukan cuma
// baca dari siswa_profiles) karena Validasi Data #1 & #5 bilang nilainya
// dihitung ULANG tiap pendaftaran baru, tapi TETAP selama pendaftaran itu
// berjalan meski siswa ulang tahun di tengah periode.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->restrictOnDelete();
            $table->foreignId('periode_id')->constrained('periode_pendaftaran')->restrictOnDelete();

            // Snapshot saat pendaftaran diajukan (Validasi Data #1)
            $table->enum('kategori_usia_saat_daftar', ['di_bawah_13', '13_sampai_17', 'dewasa']);
            $table->enum('jenjang_saat_daftar', ['TK', 'SD', 'SMP', 'SMA']);

            // F5, Validasi Data #2: gate persetujuan Wali (null = belum disetujui)
            $table->timestamp('wali_approved_at')->nullable();

            $table->enum('metode_bayar', ['lunas', 'dp_cicilan']);

            $table->enum('status', [
                'menunggu_pembayaran',   // DP/lunas belum dibayar dan/atau belum disetujui wali
                'terdaftar',             // DP/lunas dibayar DAN (kalau perlu) wali sudah setuju
                'tertunggak',            // cicilan telat, dalam masa tenggang 3 hari kerja
                'dibatalkan_timeout',    // Business Rule #2: >24 jam belum bayar/belum disetujui
                'dibatalkan_tunggakan',  // Business Rule #7: >7 hari kerja telat cicilan
                'dibatalkan_siswa',      // F14: siswa mengajukan pembatalan sendiri
                'dibatalkan_pelanggaran',// F7: admin batalkan karena kecurigaan/pelanggaran
                'dibatalkan_kuota',      // F14: admin batalkan krn kuota minimum tak terpenuhi
            ])->default('menunggu_pembayaran');

            $table->timestamp('diajukan_pada')->useCurrent();
            $table->timestamps();

            // Business Rule #9: satu siswa tidak boleh 2 pendaftaran aktif dengan
            // jadwal bentrok pada periode sama — dicek di service layer (butuh join
            // ke tabel kelas untuk baca jadwal), bukan constraint DB langsung.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};
