<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Kelas;

class KelasService
{
    /**
     * Business Rule #6: Satu guru tidak boleh dua kelas dengan jadwal
     * bentrok (hari sama + jam beririsan) di periode yang sama.
     */
    public function bentrokJadwalGuru(array $data, ?int $excludeKelasId = null): bool
    {
        return Kelas::where('guru_id', $data['guru_id'])
            ->where('periode_id', $data['periode_id'])
            ->where('hari', $data['hari'])
            ->where('status', 'aktif')
            ->when($excludeKelasId, fn ($q) => $q->where('id', '!=', $excludeKelasId))
            ->where(function ($q) use ($data) {
                $q->where('jam_mulai', '<', $data['jam_selesai'])
                    ->where('jam_selesai', '>', $data['jam_mulai']);
            })
            ->exists();
    }

    public function createKelas(array $data): Kelas
    {
        return Kelas::create($data + [
            'kuota_terisi' => 0,
            'status' => 'aktif',
        ]);
    }
}
