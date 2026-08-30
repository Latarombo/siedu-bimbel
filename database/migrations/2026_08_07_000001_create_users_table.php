<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// F1: Registrasi & login terpusat berbasis role (admin/guru/siswa).
// Satu akun mewakili satu siswa (one-to-one, Validasi Data #4).
// Akun Admin dibuat lewat seeder, bukan form publik (Ketentuan Penggunaan #3).
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->enum('role', ['admin', 'guru', 'siswa', 'orang_tua']);
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable(); // dipakai untuk guru & wali/notifikasi
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
