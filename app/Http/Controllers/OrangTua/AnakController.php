<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\Anak;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnakController extends Controller
{
    public function index(Request $request): View
    {
        $anak = $request->user()->anak()->latest()->get();

        return view('orang-tua.anak.index', compact('anak'));
    }

    public function create(): View
    {
        return view('orang-tua.anak.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|after:'.now()->subYears(100)->format('Y-m-d').'|before_or_equal:today',
            'jenjang_terakhir' => 'nullable|in:TK,SD,SMP,SMA',
        ]);

        $request->user()->anak()->create($validated);

        return redirect()->route('orang-tua.anak.index')
            ->with('status', 'Profil anak berhasil ditambahkan.');
    }

    public function edit(Anak $anak): View
    {
        $this->authorizeAkses($anak);

        return view('orang-tua.anak.edit', compact('anak'));
    }

    public function update(Request $request, Anak $anak): RedirectResponse
    {
        $this->authorizeAkses($anak);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|after:'.now()->subYears(100)->format('Y-m-d').'|before_or_equal:today',
            'jenjang_terakhir' => 'nullable|in:TK,SD,SMP,SMA',
        ]);

        $anak->update($validated);

        return redirect()->route('orang-tua.anak.index')
            ->with('status', 'Profil anak berhasil diperbarui.');
    }

    public function destroy(Anak $anak): RedirectResponse
    {
        $this->authorizeAkses($anak);

        if ($anak->pendaftaran()->exists()) {
            return back()->with('error', 'Tidak bisa dihapus, anak ini sudah punya riwayat pendaftaran.');
        }

        $anak->delete();

        return redirect()->route('orang-tua.anak.index')
            ->with('status', 'Profil anak berhasil dihapus.');
    }

    // Cegah orang tua akses/edit profil anak milik akun lain lewat URL manual
    private function authorizeAkses(Anak $anak): void
    {
        abort_unless($anak->orang_tua_id === request()->user()->id, 403);
    }
}
