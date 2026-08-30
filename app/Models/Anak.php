<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Anak extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'anak';

    protected $fillable = [
        'orang_tua_id', 'nama', 'tanggal_lahir', 'jenjang_terakhir',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    public function orangTua(): BelongsTo
    {
        return $this->belongsTo(User::class, 'orang_tua_id');
    }

    public function pendaftaran(): HasMany
    {
        return $this->hasMany(Pendaftaran::class);
    }
}
