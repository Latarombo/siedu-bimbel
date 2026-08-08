<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\PeriodePendaftaran;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class KelasController extends Controller
{
    public function index(): View
    {
        $kelas = Kelas::with(['mataPelajaran', 'guru', 'periode'])
            ->latest()
            ->paginate(10);

        return view('admin.kelas.index', compact('kelas'));
    }

    public function create(): View
    {
        [$mataPelajaran, $guru, $periode] = $this->dependencies();

        return view('admin.kelas.create', compact('mataPelajaran', 'guru', 'periode'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        if ($this->bentrokJadwalGuru($validated)) {
            return back()->withInput()
                ->with('error', 'Guru ini sudah punya kelas lain dengan jadwal bentrok di periode yang sama (Business Rule #6).');
        }

        Kelas::create($validated + ['kuota_terisi' => 0, 'status' => 'aktif']);

        return redirect()->route('admin.kelas.index')
            ->with('status', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kelas): View
    {
        [$mataPelajaran, $guru, $periode] = $this->dependencies();

        return view('admin.kelas.edit', compact('kelas', 'mataPelajaran', 'guru', 'periode'));
    }

    public function update(Request $request, Kelas $kelas): RedirectResponse
    {
        $validated = $this->validated($request);

        if ($this->bentrokJadwalGuru($validated, excludeKelasId: $kelas->id)) {
            return back()->withInput()
                ->with('error', 'Guru ini sudah punya kelas lain dengan jadwal bentrok di periode yang sama (Business Rule #6).');
        }

        // Kuota terisi tidak boleh diubah manual dari form — itu terkunci oleh
        // proses pendaftaran siswa (Business Rule #1), bukan input admin.
        $kelas->update($validated);

        return redirect()->route('admin.kelas.index')
            ->with('status', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kelas): RedirectResponse
    {
        if ($kelas->pendaftaran()->exists()) {
            return back()->with('error', 'Tidak bisa dihapus, sudah ada siswa yang mendaftar ke kelas ini.');
        }

        $kelas->delete();

        return redirect()->route('admin.kelas.index')
            ->with('status', 'Kelas berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'guru_id' => 'required|exists:users,id',
            'periode_id' => 'required|exists:periode_pendaftaran,id',
            'kuota_maksimum' => 'required|integer|min:1',
            'kuota_minimum' => 'required|integer|min:1|lte:kuota_maksimum',
            'hari' => ['required', Rule::in(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'])],
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'biaya_periode' => 'required|numeric|min:0',
            'biaya_dp' => 'nullable|numeric|min:0|lte:biaya_periode',
        ]);
    }

    /**
     * Business Rule #6: satu guru tidak boleh dua kelas dengan jadwal
     * bentrok (hari sama + jam beririsan) di periode yang sama.
     * Ini yang tidak bisa dicek lewat constraint DB biasa — harus query manual.
     */
    private function bentrokJadwalGuru(array $data, ?int $excludeKelasId = null): bool
    {
        return Kelas::where('guru_id', $data['guru_id'])
            ->where('periode_id', $data['periode_id'])
            ->where('hari', $data['hari'])
            ->where('status', 'aktif')
            ->when($excludeKelasId, fn ($q) => $q->where('id', '!=', $excludeKelasId))
            ->where(function ($q) use ($data) {
                // beririsan kalau: mulai_baru < selesai_lama DAN selesai_baru > mulai_lama
                $q->where('jam_mulai', '<', $data['jam_selesai'])
                  ->where('jam_selesai', '>', $data['jam_mulai']);
            })
            ->exists();
    }

    private function dependencies(): array
    {
        return [
            MataPelajaran::orderBy('nama')->get(),
            User::where('role', 'guru')->orderBy('name')->get(),
            PeriodePendaftaran::where('status', 'dibuka')->orderBy('tanggal_mulai')->get(),
        ];
    }
}
