<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Ganti role 'siswa' jadi 'orang_tua'. Tambah 2 kolom consent (gambar 1):
// checkbox privasi diri sendiri + checkbox persetujuan data anak.
// Disimpan sebagai timestamp (bukan boolean) supaya jadi bukti hukum
// kapan persetujuan diberikan (sama seperti kasus Apple/FTC yang jadi
// preseden kita bahas — bukti tercatat, bukan sekadar centang lalu hilang).
return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'guru', 'siswa', 'orang_tua') NOT NULL");
        }

        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('privasi_disetujui_at')->nullable()->after('phone');
            $table->timestamp('wali_disetujui_at')->nullable()->after('privasi_disetujui_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['privasi_disetujui_at', 'wali_disetujui_at']);
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'guru', 'siswa', 'orang_tua') NOT NULL");
        }
    }
};
