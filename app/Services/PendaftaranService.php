<?php

namespace App\Services;

use App\Models\Anak;
use App\Models\Kelas;
use App\Models\Pendaftaran;
use App\Models\Pembayaran;
use App\Models\PeriodePendaftaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PendaftaranService
{
    /**
     * Q3-Q7: Validasi + reserve kuota atomic. BR#14 pembayaran record.
     *
     * @throws ValidationException
     */
    public function createPendaftaran(int $orangTuaId, int $anakId, int $kelasId): Pendaftaran
    {
        return DB::transaction(function () use ($orangTuaId, $anakId, $kelasId) {
            $anak = Anak::where('id', $anakId)
                ->where('orang_tua_id', $orangTuaId)
                ->first();

            if (! $anak) {
                throw ValidationException::withMessages([
                    'anak_id' => 'Profil anak tidak ditemukan atau bukan milik Anda.',
                ]);
            }

            $user = $anak->orangTua;

            if (! $user->sudahMenyetujuiConsent()) {
                throw ValidationException::withMessages([
                    'consent' => 'Anda harus menyetujui kebijakan privasi dan persetujuan wali sebelum mendaftar.',
                ]);
            }

            if (! $user->hasVerifiedEmail()) {
                throw ValidationException::withMessages([
                    'email' => 'Email harus diverifikasi sebelum mendaftar.',
                ]);
            }

            // BR#13: jenjang wajib sebelum daftar
            if ($anak->jenjang_terakhir === null) {
                throw ValidationException::withMessages([
                    'anak_id' => 'Lengkapi jenjang di profil anak dulu sebelum mendaftar.',
                ]);
            }

            $kelas = Kelas::with('mataPelajaran')->where('id', $kelasId)
                ->lockForUpdate()
                ->first();

            if (! $kelas) {
                throw ValidationException::withMessages([
                    'kelas_id' => 'Kelas tidak ditemukan.',
                ]);
            }

            if ($kelas->status !== 'aktif') {
                throw ValidationException::withMessages([
                    'kelas_id' => 'Kelas tidak aktif.',
                ]);
            }

            $periode = PeriodePendaftaran::find($kelas->periode_id);

            if (! $periode || $periode->status !== 'dibuka') {
                throw ValidationException::withMessages([
                    'kelas_id' => 'Periode pendaftaran untuk kelas ini belum dibuka atau sudah ditutup.',
                ]);
            }

            if ($kelas->kuotaPenuh()) {
                throw ValidationException::withMessages([
                    'kelas_id' => 'Kuota kelas sudah penuh.',
                ]);
            }

            // BR#13: jenjang kelas (via mata pelajaran) harus match
            $jenjangKelas = $kelas->mataPelajaran->jenjang ?? null;
            if ($jenjangKelas !== null && $jenjangKelas !== $anak->jenjang_terakhir) {
                throw ValidationException::withMessages([
                    'kelas_id' => 'Kelas tidak sesuai jenjang anak ('.$anak->jenjang_terakhir.' vs '.$jenjangKelas.').',
                ]);
            }

            // BR#9: jadwal bentrok anak (hari sama + jam beririsan, periode sama, status aktif)
            $bentrok = Pendaftaran::where('anak_id', $anakId)
                ->where('periode_id', $kelas->periode_id)
                ->whereNotIn('status', ['dibatalkan_timeout', 'dibatalkan_tunggakan', 'dibatalkan_siswa', 'dibatalkan_pelanggaran', 'dibatalkan_kuota'])
                ->whereHas('kelas', function ($q) use ($kelas) {
                    $q->where('hari', $kelas->hari)
                        ->where('jam_mulai', '<', $kelas->jam_selesai)
                        ->where('jam_selesai', '>', $kelas->jam_mulai);
                })->exists();

            if ($bentrok) {
                throw ValidationException::withMessages([
                    'kelas_id' => 'Jadwal bentrok dengan kelas lain yang sudah diambil anak di periode ini.',
                ]);
            }

            // Q10: 1 anak = 1 pendaftaran aktif per periode (sudah tercakup bentrok, tapi keep simple check)
            $exists = Pendaftaran::where('anak_id', $anakId)
                ->where('periode_id', $kelas->periode_id)
                ->whereNotIn('status', ['dibatalkan_timeout', 'dibatalkan_tunggakan', 'dibatalkan_siswa', 'dibatalkan_pelanggaran', 'dibatalkan_kuota'])
                ->exists();

            if ($exists) {
                throw ValidationException::withMessages([
                    'anak_id' => 'Anak ini sudah terdaftar di periode yang sama.',
                ]);
            }

            // Q5 + BR#12: tentukan metode bayar dari Kelas.biaya_dp
            $metode = $kelas->biaya_dp !== null && $kelas->biaya_dp > 0 ? 'dp_cicilan' : 'lunas';

            $pendaftaran = Pendaftaran::create([
                'anak_id' => $anakId,
                'kelas_id' => $kelasId,
                'periode_id' => $kelas->periode_id,
                'jenjang_saat_daftar' => $anak->jenjang_terakhir ?? 'SD',
                'metode_bayar' => $metode,
                'status' => 'menunggu_pembayaran',
                'diajukan_pada' => now(),
            ]);

            $kelas->increment('kuota_terisi');

            return $pendaftaran;
        });
    }

    /**
     * Create payment records for a new pendaftaran (BR#14)
     */
    public function createPaymentRecords(Pendaftaran $pendaftaran): void
    {
        $kelas = $pendaftaran->kelas;
        $metode = $pendaftaran->metode_bayar;

        if ($metode === 'dp_cicilan') {
            Pembayaran::create([
                'pendaftaran_id' => $pendaftaran->id,
                'tipe' => 'dp',
                'jumlah' => $kelas->biaya_dp,
                'jatuh_tempo' => now()->addDay()->toDateString(),
                'status' => 'pending',
            ]);
        } else {
            Pembayaran::create([
                'pendaftaran_id' => $pendaftaran->id,
                'tipe' => 'lunas',
                'jumlah' => $kelas->biaya_periode,
                'jatuh_tempo' => null,
                'status' => 'pending',
            ]);
        }
    }
}