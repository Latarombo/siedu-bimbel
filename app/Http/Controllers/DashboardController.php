<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * F1: Login terpusat berbasis role. Dashboard jadi satu pintu masuk,
     * tapi kontennya beda tergantung role yang login.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        return match ($user->role) {
            'admin' => $this->admin($user),
            'guru' => $this->guru($user),
            'siswa' => $this->siswa($user),
        };
    }

    private function admin($user): View
    {
        // F15: ringkasan cepat untuk Admin
        $totalSiswa = \App\Models\User::where('role', 'siswa')->count();
        $totalGuru = \App\Models\User::where('role', 'guru')->count();
        $pendaftaranAktif = \App\Models\Pendaftaran::where('status', 'terdaftar')->count();
        $menungguPersetujuan = \App\Models\Pendaftaran::where('status', 'menunggu_pembayaran')->count();

        return view('dashboard.admin', compact(
            'totalSiswa', 'totalGuru', 'pendaftaranAktif', 'menungguPersetujuan'
        ));
    }

    private function guru($user): View
    {
        // F12/F13: kelas yang diampu guru ini di periode aktif
        $kelasDiampu = $user->kelasDiampu()
            ->with(['mataPelajaran', 'periode'])
            ->whereHas('periode', fn ($q) => $q->where('status', 'dibuka'))
            ->get();

        return view('dashboard.guru', compact('kelasDiampu'));
    }

    private function siswa($user): View
    {
        // F4/F5: status pendaftaran terbaru milik siswa ini
        $pendaftaran = $user->pendaftaran()
            ->with(['kelas.mataPelajaran', 'periode'])
            ->latest('diajukan_pada')
            ->get();

        $butuhPersetujuanWali = $pendaftaran
            ->where('status', 'menunggu_pembayaran')
            ->filter(fn ($p) => $p->butuhPersetujuanWali() && ! $p->sudahDisetujuiWali());

        return view('dashboard.siswa', compact('pendaftaran', 'butuhPersetujuanWali'));
    }
}