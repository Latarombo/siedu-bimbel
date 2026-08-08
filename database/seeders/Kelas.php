<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'mata_pelajaran_id', 'guru_id', 'periode_id',
        'kuota_maksimum', 'kuota_minimum', 'kuota_terisi',
        'hari', 'jam_mulai', 'jam_selesai',
        'biaya_periode', 'biaya_dp', 'status',
    ];

    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodePendaftaran::class, 'periode_id');
    }

    public function pendaftaran(): HasMany
    {
        return $this->hasMany(Pendaftaran::class);
    }

    // Business Rule #1: kuota dianggap penuh begitu kuota_terisi = kuota_maksimum
    public function kuotaPenuh(): bool
    {
        return $this->kuota_terisi >= $this->kuota_maksimum;
    }

    // Business Rule #10: dicek saat periode ditutup, dipanggil dari scheduled job
    public function kuotaMinimumTerpenuhi(): bool
    {
        return $this->kuota_terisi >= $this->kuota_minimum;
    }
}
