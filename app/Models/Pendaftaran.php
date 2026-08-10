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
        'anak_id', 'kelas_id', 'periode_id',
        'metode_bayar', 'status', 'diajukan_pada',
    ];

    protected function casts(): array
    {
        return [
            'diajukan_pada' => 'datetime',
        ];
    }

    public function anak(): BelongsTo
    {
        return $this->belongsTo(Anak::class);
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

    // Business Rule #2 (versi baru): syarat "terdaftar" cukup pembayaran
    // berhasil — consent Wali sudah dipenuhi di tingkat akun Orang Tua
    // saat registrasi (User::sudahMenyetujuiConsent()), tidak dicek ulang
    // per-pendaftaran seperti desain lama.
    public function memenuhiSyaratTerdaftar(): bool
    {
        return $this->pembayaran()
            ->whereIn('tipe', ['dp', 'lunas'])
            ->where('status', 'berhasil')
            ->exists();
    }
}
