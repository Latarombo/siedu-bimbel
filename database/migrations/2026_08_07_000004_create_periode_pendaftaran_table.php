<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// F10 & Business Rule #8: satu Pendaftaran berlaku untuk satu periode
// (satu tahun ajaran). Business Rule #10: kuota minimum dicek otomatis
// saat periode ditutup.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periode_pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // contoh: "Tahun Ajaran 2026/2027"
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->date('tanggal_tutup_pendaftaran');
            $table->enum('status', ['dibuka', 'ditutup', 'selesai'])->default('dibuka');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periode_pendaftaran');
    }
};
