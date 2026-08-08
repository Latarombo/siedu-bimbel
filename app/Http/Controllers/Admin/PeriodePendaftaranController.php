<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeriodePendaftaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PeriodePendaftaranController extends Controller
{
    public function index(): View
    {
        $periode = PeriodePendaftaran::latest('tanggal_mulai')->paginate(10);

        return view('admin.periode.index', compact('periode'));
    }

    public function create(): View
    {
        return view('admin.periode.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        PeriodePendaftaran::create($validated);

        return redirect()->route('admin.periode.index')
            ->with('status', 'Periode pendaftaran berhasil ditambahkan.');
    }

    public function edit(PeriodePendaftaran $periode): View
    {
        return view('admin.periode.edit', compact('periode'));
    }

    public function update(Request $request, PeriodePendaftaran $periode): RedirectResponse
    {
        $validated = $this->validated($request);

        $periode->update($validated);

        return redirect()->route('admin.periode.index')
            ->with('status', 'Periode pendaftaran berhasil diperbarui.');
    }

    public function destroy(PeriodePendaftaran $periode): RedirectResponse
    {
        if ($periode->kelas()->exists() || $periode->pendaftaran()->exists()) {
            return back()->with('error', 'Tidak bisa dihapus, periode ini sudah punya kelas/pendaftaran terkait.');
        }

        $periode->delete();

        return redirect()->route('admin.periode.index')
            ->with('status', 'Periode pendaftaran berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'tanggal_tutup_pendaftaran' => 'required|date|before_or_equal:tanggal_mulai',
            'status' => 'required|in:dibuka,ditutup,selesai',
        ]);
    }
}
