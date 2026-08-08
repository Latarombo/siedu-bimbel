<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';

    protected $fillable = [
        'pendaftaran_id',
        'dicatat_oleh',
        'tanggal_pertemuan',
        'status',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pertemuan' => 'date',
        ];
    }

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }
}
