<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['role', 'name', 'email', 'phone', 'password', 'privasi_disetujui_at', 'wali_disetujui_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'privasi_disetujui_at' => 'datetime',
            'wali_disetujui_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // F11 (versi baru): satu akun Orang Tua bisa punya banyak profil Anak
    public function anak(): HasMany
    {
        return $this->hasMany(Anak::class, 'orang_tua_id');
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

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    public function isOrangTua(): bool
    {
        return $this->role === 'orang_tua';
    }

    // Bukti hukum consent sudah diberikan (dicek sebelum bisa akses fitur pendaftaran)
    public function sudahMenyetujuiConsent(): bool
    {
        return $this->privasi_disetujui_at !== null && $this->wali_disetujui_at !== null;
    }
}
