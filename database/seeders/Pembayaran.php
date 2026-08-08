<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'pendaftaran_id', 'tipe', 'cicilan_ke', 'jumlah',
        'jatuh_tempo', 'dibayar_pada', 'status',
        'percobaan_retry', 'referensi_gateway',
    ];

    protected function casts(): array
    {
        return [
            'jatuh_tempo' => 'date',
            'dibayar_pada' => 'datetime',
            'jumlah' => 'decimal:2',
        ];
    }

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    // Business Rule #4: tenggang 7 hari kerja sebelum dianggap tertunggak berat
    public function terlambat(): bool
    {
        return $this->status === 'pending'
            && $this->jatuh_tempo !== null
            && $this->jatuh_tempo->isPast();
    }
}
