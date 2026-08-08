<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanPembatalan extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_pembatalan';

    protected $fillable = [
        'pendaftaran_id',
        'diajukan_oleh',
        'alasan',
        'minta_refund_khusus',
        'status',
        'diproses_oleh',
        'catatan_admin',
        'diproses_pada',
    ];

    protected function casts(): array
    {
        return [
            'minta_refund_khusus' => 'boolean',
            'diproses_pada' => 'datetime',
        ];
    }

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function pengaju(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diajukan_oleh');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }
}
