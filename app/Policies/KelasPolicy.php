<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Kelas;
use App\Models\User;

class KelasPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Kelas $kelas): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Kelas $kelas): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Kelas $kelas): bool
    {
        return $user->isAdmin() && ! $kelas->pendaftaran()->exists();
    }

    public function restore(User $user, Kelas $kelas): bool
    {
        return false;
    }

    public function forceDelete(User $user, Kelas $kelas): bool
    {
        return false;
    }
}
