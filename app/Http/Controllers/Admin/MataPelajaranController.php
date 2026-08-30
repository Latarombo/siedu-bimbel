<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMataPelajaranRequest;
use App\Http\Requests\UpdateMataPelajaranRequest;
use App\Models\MataPelajaran;
use Illuminate\Http\RedirectResponse;
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

    public function store(StoreMataPelajaranRequest $request): RedirectResponse
    {
        MataPelajaran::create($request->validated());

        return redirect()->route('admin.mata-pelajaran.index')
            ->with('status', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit(MataPelajaran $mataPelajaran): View
    {
        return view('admin.mata-pelajaran.edit', compact('mataPelajaran'));
    }

    public function update(UpdateMataPelajaranRequest $request, MataPelajaran $mataPelajaran): RedirectResponse
    {
        $mataPelajaran->update($request->validated());

        return redirect()->route('admin.mata-pelajaran.index')
            ->with('status', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(MataPelajaran $mataPelajaran): RedirectResponse
    {
        if ($mataPelajaran->kelas()->exists()) {
            return back()->with('error', 'Tidak bisa dihapus, masih dipakai oleh kelas yang ada.');
        }

        $mataPelajaran->delete();

        return redirect()->route('admin.mata-pelajaran.index')
            ->with('status', 'Mata pelajaran berhasil dihapus.');
    }
}
