<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran';

    protected $fillable = [
        'siswa_id', 'kelas_id', 'periode_id',
        'kategori_usia_saat_daftar', 'jenjang_saat_daftar',
        'wali_approved_at', 'metode_bayar', 'status', 'diajukan_pada',
    ];

    protected function casts(): array
    {
        return [
            'wali_approved_at' => 'datetime',
            'diajukan_pada' => 'datetime',
        ];
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodePendaftaran::class, 'periode_id');
    }

    public function pembayaran(): HasMany
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function presensi(): HasMany
    {
        return $this->hasMany(Presensi::class);
    }

    public function nilaiProgres(): HasMany
    {
        return $this->hasMany(NilaiProgres::class);
    }

    public function pengajuanPembatalan(): HasOne
    {
        return $this->hasOne(PengajuanPembatalan::class);
    }

    // F5, Validasi Data #2: gate wajib untuk siswa di bawah 18 tahun
    public function butuhPersetujuanWali(): bool
    {
        return $this->kategori_usia_saat_daftar !== 'dewasa';
    }

    public function sudahDisetujuiWali(): bool
    {
        return ! $this->butuhPersetujuanWali() || $this->wali_approved_at !== null;
    }

    // Business Rule #2: syarat status "terdaftar" = bayar DP/lunas DAN
    // (kalau perlu) disetujui wali, dalam 1x24 jam sejak diajukan
    public function memenuhiSyaratTerdaftar(): bool
    {
        $sudahBayar = $this->pembayaran()
            ->whereIn('tipe', ['dp', 'lunas'])
            ->where('status', 'berhasil')
            ->exists();

        return $sudahBayar && $this->sudahDisetujuiWali();
    }
}
