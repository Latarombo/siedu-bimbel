<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'role',
        'name',
        'email',
        'phone',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi khusus role 'siswa' (F11)
    public function siswaProfile(): HasOne
    {
        return $this->hasOne(SiswaProfile::class);
    }

    // Relasi khusus role 'siswa' — semua pendaftaran milik akun ini
    public function pendaftaran(): HasMany
    {
        return $this->hasMany(Pendaftaran::class, 'siswa_id');
    }

    // Relasi khusus role 'guru' — kelas yang diampu (F10)
    public function kelasDiampu(): HasMany
    {
        return $this->hasMany(Kelas::class, 'guru_id');
    }

    // Relasi khusus role 'guru' — presensi yang dicatat (F12)
    public function presensiDicatat(): HasMany
    {
        return $this->hasMany(Presensi::class, 'dicatat_oleh');
    }

    // Relasi khusus role 'guru' — nilai yang diinput (F13)
    public function nilaiDiinput(): HasMany
    {
        return $this->hasMany(NilaiProgres::class, 'dicatat_oleh');
    }

    // Helper role check, dipakai di middleware
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }
}
