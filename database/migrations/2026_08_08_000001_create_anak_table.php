<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Menggantikan siswa_profiles: anak sekarang PROFIL di bawah akun Orang Tua,
// bukan akun/login terpisah. Satu Orang Tua bisa punya banyak anak (1:banyak).
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orang_tua_id')->constrained('users')->cascadeOnDelete();

            $table->string('nama');
            $table->date('tanggal_lahir');
            $table->enum('jenjang_terakhir', ['TK', 'SD', 'SMP', 'SMA'])->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anak');
    }
};
