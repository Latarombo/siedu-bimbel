<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// F13 & Validasi Data #3: format nilai menyesuaikan jenjang
// (kualitatif untuk TK/SD, kuantitatif untuk SMP/SMA).
// Kedua kolom nullable, diisi salah satu tergantung jenjang_saat_daftar
// pada tabel pendaftaran terkait.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilai_progres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftaran')->cascadeOnDelete();
            $table->foreignId('dicatat_oleh')->constrained('users')->restrictOnDelete(); // guru

            $table->date('tanggal');
            $table->text('catatan_kualitatif')->nullable();  // TK/SD
            $table->decimal('nilai_kuantitatif', 5, 2)->nullable(); // SMP/SMA

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_progres');
    }
};
