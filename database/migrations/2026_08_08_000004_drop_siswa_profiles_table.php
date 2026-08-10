<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('siswa_profiles');
    }

    public function down(): void
    {
        Schema::create('siswa_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal_lahir');
            $table->enum('jenjang_terakhir', ['TK', 'SD', 'SMP', 'SMA'])->nullable();
            $table->string('nama_wali')->nullable();
            $table->string('no_hp_wali')->nullable();
            $table->string('email_wali')->nullable();
            $table->timestamps();
            $table->unique('user_id');
        });
    }
};
