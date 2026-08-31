<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PendaftaranPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isOrangTua() || $user->isAdmin();
    }

    public function view(User $user, Pendaftaran $pendaftaran): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isOrangTua() && $pendaftaran->anak->orang_tua_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isOrangTua() && $user->sudahMenyetujuiConsent() && $user->hasVerifiedEmail();
    }

    public function delete(User $user, Pendaftaran $pendaftaran): bool
    {
        return $user->isOrangTua() && $pendaftaran->anak->orang_tua_id === $user->id;
    }
}
