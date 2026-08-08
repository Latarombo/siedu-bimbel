<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// F6 & Business Rule #7: pembayaran lunas di muka, atau DP + cicilan bulanan
// tokenized via payment gateway. Satu baris = satu tagihan (DP / satu kali
// cicilan / lunas).
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendaftaran_id')->constrained('pendaftaran')->restrictOnDelete();

            $table->enum('tipe', ['dp', 'cicilan', 'lunas']);
            $table->unsignedInteger('cicilan_ke')->nullable(); // urutan cicilan, null kalau bukan tipe cicilan

            $table->decimal('jumlah', 12, 2);
            $table->date('jatuh_tempo')->nullable(); // null untuk DP/lunas (dibayar saat itu juga)
            $table->timestamp('dibayar_pada')->nullable();

            $table->enum('status', ['pending', 'berhasil', 'gagal', 'tertunggak'])->default('pending');
            $table->unsignedTinyInteger('percobaan_retry')->default(0); // max 1x retry otomatis (F6)

            $table->string('referensi_gateway')->nullable(); // ID transaksi dari Midtrans/Xendit
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
