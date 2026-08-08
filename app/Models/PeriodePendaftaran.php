<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodePendaftaran extends Model
{
    use HasFactory;

    protected $table = 'periode_pendaftaran';

    protected $fillable = [
        'nama',
        'tanggal_mulai',
        'tanggal_selesai',
        'tanggal_tutup_pendaftaran',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'tanggal_tutup_pendaftaran' => 'date',
        ];
    }

    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class, 'periode_id');
    }

    public function pendaftaran(): HasMany
    {
        return $this->hasMany(Pendaftaran::class, 'periode_id');
    }
}
