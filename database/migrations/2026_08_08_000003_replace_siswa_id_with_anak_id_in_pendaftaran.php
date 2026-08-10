<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// pendaftaran.siswa_id -> anak_id, karena yang didaftarkan sekarang adalah
// entri di tabel `anak`, bukan langsung akun user.
// wali_approved_at & kategori_usia_saat_daftar DIHAPUS: consent sekarang
// diberikan sekali di tingkat akun Orang Tua (lihat migration 000002),
// bukan per-pendaftaran seperti desain lama.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropForeign(['siswa_id']);
            $table->dropColumn(['siswa_id', 'wali_approved_at', 'kategori_usia_saat_daftar']);

            $table->foreignId('anak_id')->after('id')->constrained('anak')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropForeign(['anak_id']);
            $table->dropColumn('anak_id');

            $table->foreignId('siswa_id')->constrained('users')->restrictOnDelete();
            $table->timestamp('wali_approved_at')->nullable();
            $table->enum('kategori_usia_saat_daftar', ['di_bawah_13', '13_sampai_17', 'dewasa']);
        });
    }
};
