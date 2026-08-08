<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajaran';

    protected $fillable = ['nama', 'jenjang', 'deskripsi'];

    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class);
    }
}
