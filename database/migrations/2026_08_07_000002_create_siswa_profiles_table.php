<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// F11: Data Wali (nama, no. HP, email) diisi/diperbarui lewat profil Siswa,
// tanpa akun Wali terpisah (Hak Akses #2).
// Validasi Data #5: jenjang dikonfirmasi ulang tiap periode baru, jadi kolom ini
// adalah nilai TERAKHIR yang diisi siswa, bukan sumber kebenaran final saat
// mendaftar (nilai final per pendaftaran disimpan di tabel `pendaftaran`).
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswa_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->date('tanggal_lahir');
            $table->enum('jenjang_terakhir', ['TK', 'SD', 'SMP', 'SMA'])->nullable();

            // Data Wali (F11) - nempel di profil siswa, bukan tabel/akun terpisah
            $table->string('nama_wali')->nullable();
            $table->string('no_hp_wali')->nullable();
            $table->string('email_wali')->nullable();

            $table->timestamps();

            $table->unique('user_id'); // 1 akun siswa = 1 profil (Validasi Data #4)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa_profiles');
    }
};
