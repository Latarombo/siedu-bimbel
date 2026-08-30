<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Anak;
use App\Models\User;

class AnakPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Anak $anak): bool
    {
        return $user->isOrangTua() && $anak->orang_tua_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isOrangTua();
    }

    public function update(User $user, Anak $anak): bool
    {
        return $user->isOrangTua() && $anak->orang_tua_id === $user->id;
    }

    public function delete(User $user, Anak $anak): bool
    {
        return $user->isOrangTua()
            && $anak->orang_tua_id === $user->id
            && ! $anak->pendaftaran()->exists();
    }

    public function restore(User $user, Anak $anak): bool
    {
        return false;
    }

    public function forceDelete(User $user, Anak $anak): bool
    {
        return false;
    }
}
