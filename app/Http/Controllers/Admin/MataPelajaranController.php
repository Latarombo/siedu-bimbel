<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MataPelajaranController extends Controller
{
    public function index(): View
    {
        $mataPelajaran = MataPelajaran::latest()->paginate(10);

        return view('admin.mata-pelajaran.index', compact('mataPelajaran'));
    }

    public function create(): View
    {
        return view('admin.mata-pelajaran.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jenjang' => 'required|in:TK,SD,SMP,SMA',
            'deskripsi' => 'nullable|string',
        ]);

        MataPelajaran::create($validated);

        return redirect()->route('admin.mata-pelajaran.index')
            ->with('status', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit(MataPelajaran $mataPelajaran): View
    {
        return view('admin.mata-pelajaran.edit', compact('mataPelajaran'));
    }

    public function update(Request $request, MataPelajaran $mataPelajaran): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jenjang' => 'required|in:TK,SD,SMP,SMA',
            'deskripsi' => 'nullable|string',
        ]);

        $mataPelajaran->update($validated);

        return redirect()->route('admin.mata-pelajaran.index')
            ->with('status', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(MataPelajaran $mataPelajaran): RedirectResponse
    {
        // F10: kelas terikat via foreign key restrictOnDelete — cegah hapus
        // kalau masih ada kelas yang memakai mata pelajaran ini.
        if ($mataPelajaran->kelas()->exists()) {
            return back()->with('error', 'Tidak bisa dihapus, masih dipakai oleh kelas yang ada.');
        }

        $mataPelajaran->delete();

        return redirect()->route('admin.mata-pelajaran.index')
            ->with('status', 'Mata pelajaran berhasil dihapus.');
    }
}
