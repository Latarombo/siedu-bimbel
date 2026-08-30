<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        return match ($user->role) {
            'admin' => $this->admin($user),
            'guru' => $this->guru($user),
            'orang_tua' => $this->orangTua($user),
        };
    }

    private function admin($user): View
    {
        $totalOrangTua = User::where('role', 'orang_tua')->count();
        $totalGuru = User::where('role', 'guru')->count();
        $pendaftaranAktif = Pendaftaran::where('status', 'terdaftar')->count();
        $menungguPembayaran = Pendaftaran::where('status', 'menunggu_pembayaran')->count();

        return view('dashboard.admin', compact(
            'totalOrangTua',
            'totalGuru',
            'pendaftaranAktif',
            'menungguPembayaran'
        ));
    }

    private function guru($user): View
    {
        $kelasDiampu = $user->kelasDiampu()
            ->with(['mataPelajaran', 'periode'])
            ->whereHas('periode', fn ($q) => $q->where('status', 'dibuka'))
            ->get();

        return view('dashboard.guru', compact('kelasDiampu'));
    }

    private function orangTua($user): View
    {
        // F4/F5 (versi baru): semua anak milik akun ini + status pendaftaran tiap anak
        $anak = $user->anak()
            ->with(['pendaftaran.kelas.mataPelajaran', 'pendaftaran.periode'])
            ->get();

        return view('dashboard.orang-tua', compact('anak'));
    }
}
