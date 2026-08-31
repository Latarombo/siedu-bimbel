<?php

declare(strict_types=1);

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePendaftaranRequest;
use App\Models\Anak;
use App\Models\Kelas;
use App\Services\PendaftaranService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PendaftaranController extends Controller
{
    public function __construct(
        private readonly PendaftaranService $pendaftaranService,
    ) {}

    public function index(): View
    {
        $pendaftaran = \App\Models\Pendaftaran::whereHas('anak', fn ($q) => $q->where('orang_tua_id', request()->user()->id))
            ->with(['anak', 'kelas.mataPelajaran', 'kelas.periode'])
            ->latest('diajukan_pada')
            ->paginate(10);

        return view('orang-tua.pendaftaran.index', compact('pendaftaran'));
    }

    public function create(): View
    {
        $this->authorize('create', \App\Models\Pendaftaran::class);
        
        $user = request()->user();
        $anak = $user->anak()->get();

        // BR#13: If any child has null jenjang_terakhir, prompt to complete profile first
        $hasNullJenjang = $anak->contains(fn ($a) => $a->jenjang_terakhir === null);
        if ($hasNullJenjang) {
            return view('orang-tua.pendaftaran.create', compact('anak'))
                ->with('error', 'Lengkapi jenjang di profil anak dulu. <a href="' . route('orang-tua.anak.index') . '">Ke profil anak</a>');
        }

        $jenjangList = $anak->pluck('jenjang_terakhir')->unique();
        $kelas = Kelas::with(['mataPelajaran', 'periode', 'guru'])
            ->aktif()
            ->kuotaTersedia()
            ->whereHas('periode', fn ($q) => $q->where('status', 'dibuka'))
            ->whereHas('mataPelajaran', fn ($q) => $q->whereIn('jenjang', $jenjangList))
            ->get();

        return view('orang-tua.pendaftaran.create', compact('anak', 'kelas'));
    }

    public function store(StorePendaftaranRequest $request): RedirectResponse
    {
        try {
            $pendaftaran = $this->pendaftaranService->createPendaftaran(
                $request->user()->id,
                (int) $request->validated('anak_id'),
                (int) $request->validated('kelas_id'),
            );
        } catch (ValidationException $e) {
            return back()->withInput()->withErrors($e->errors());
        }

        return redirect()->route('orang-tua.pendaftaran.index')
            ->with('status', 'Pendaftaran berhasil dibuat. Silakan lanjutkan pembayaran.');
    }
}
