<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class SiswaProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tanggal_lahir',
        'jenjang_terakhir',
        'nama_wali',
        'no_hp_wali',
        'email_wali',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Validasi Data #1: kategori usia dihitung ULANG tiap kali dipanggil,
    // bukan disimpan permanen di sini (nilai final per pendaftaran ada
    // snapshot-nya di tabel pendaftaran, lihat Pendaftaran::kategoriUsiaSaatIni()).
    public function kategoriUsiaSaatIni(): string
    {
        $umur = Carbon::parse($this->tanggal_lahir)->age;

        return match (true) {
            $umur < 13 => 'di_bawah_13',
            $umur <= 17 => '13_sampai_17',
            default => 'dewasa',
        };
    }

    // F5: siswa di bawah 18 tahun butuh persetujuan Wali tiap pendaftaran baru
    public function butuhPersetujuanWali(): bool
    {
        return $this->kategoriUsiaSaatIni() !== 'dewasa';
    }
}
