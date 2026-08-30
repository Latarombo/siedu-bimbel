<?php

declare(strict_types=1);

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnakRequest;
use App\Http\Requests\UpdateAnakRequest;
use App\Models\Anak;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AnakController extends Controller
{
    public function index(): View
    {
        $anak = request()->user()->anak()->latest()->get();

        return view('orang-tua.anak.index', compact('anak'));
    }

    public function create(): View
    {
        return view('orang-tua.anak.create');
    }

    public function store(StoreAnakRequest $request): RedirectResponse
    {
        $request->user()->anak()->create($request->validated());

        return redirect()->route('orang-tua.anak.index')
            ->with('status', 'Profil anak berhasil ditambahkan.');
    }

    public function edit(Anak $anak): View
    {
        $this->authorize('update', $anak);

        return view('orang-tua.anak.edit', compact('anak'));
    }

    public function update(UpdateAnakRequest $request, Anak $anak): RedirectResponse
    {
        $anak->update($request->validated());

        return redirect()->route('orang-tua.anak.index')
            ->with('status', 'Profil anak berhasil diperbarui.');
    }

    public function destroy(Anak $anak): RedirectResponse
    {
        $this->authorize('delete', $anak);

        $anak->delete();

        return redirect()->route('orang-tua.anak.index')
            ->with('status', 'Profil anak berhasil dihapus.');
    }
}
