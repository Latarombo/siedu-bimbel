<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\MataPelajaran;
use Illuminate\View\View;

class PublicPageController extends Controller
{
    // Use case "read landing page" — halaman utama sebelum Registrasi
    public function landing(): View
    {
        // Cuplikan mata pelajaran untuk ditampilkan di landing page
        $mataPelajaran = MataPelajaran::inRandomOrder()->limit(6)->get();

        return view('public.landing', compact('mataPelajaran'));
    }

    // Use case "read katalog class page" — daftar kelas yang bisa didaftar (F4)
    public function katalog(): View
    {
        $kelas = Kelas::with(['mataPelajaran', 'periode'])
            ->where('status', 'aktif')
            ->whereHas('periode', fn ($q) => $q->where('status', 'dibuka'))
            ->orderBy('mata_pelajaran_id')
            ->paginate(12);

        return view('public.katalog', compact('kelas'));
    }

    // Use case "read about page"
    public function about(): View
    {
        return view('public.about');
    }
}
