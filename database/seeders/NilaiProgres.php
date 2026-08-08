<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NilaiProgres extends Model
{
    use HasFactory;

    protected $table = 'nilai_progres';

    protected $fillable = [
        'pendaftaran_id', 'dicatat_oleh',
        'tanggal', 'catatan_kualitatif', 'nilai_kuantitatif',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'nilai_kuantitatif' => 'decimal:2',
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
