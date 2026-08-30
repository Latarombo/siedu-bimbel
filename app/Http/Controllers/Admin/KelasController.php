<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreKelasRequest;
use App\Http\Requests\UpdateKelasRequest;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\PeriodePendaftaran;
use App\Models\User;
use App\Services\KelasService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KelasController extends Controller
{
    public function __construct(
        private readonly KelasService $kelasService,
    ) {}

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

    public function store(StoreKelasRequest $request): RedirectResponse
    {
        if ($this->kelasService->bentrokJadwalGuru($request->validated())) {
            return back()->withInput()
                ->with('error', 'Guru ini sudah punya kelas lain dengan jadwal bentrok di periode yang sama (Business Rule #6).');
        }

        $this->kelasService->createKelas($request->validated());

        return redirect()->route('admin.kelas.index')
            ->with('status', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kelas): View
    {
        $this->authorize('update', $kelas);

        [$mataPelajaran, $guru, $periode] = $this->dependencies();

        return view('admin.kelas.edit', compact('kelas', 'mataPelajaran', 'guru', 'periode'));
    }

    public function update(UpdateKelasRequest $request, Kelas $kelas): RedirectResponse
    {
        if ($this->kelasService->bentrokJadwalGuru($request->validated(), excludeKelasId: $kelas->id)) {
            return back()->withInput()
                ->with('error', 'Guru ini sudah punya kelas lain dengan jadwal bentrok di periode yang sama (Business Rule #6).');
        }

        $kelas->update($request->validated());

        return redirect()->route('admin.kelas.index')
            ->with('status', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kelas): RedirectResponse
    {
        $this->authorize('delete', $kelas);

        $kelas->delete();

        return redirect()->route('admin.kelas.index')
            ->with('status', 'Kelas berhasil dihapus.');
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
