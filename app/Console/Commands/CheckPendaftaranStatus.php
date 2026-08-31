<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Pendaftaran;
use App\Models\Kelas;
use App\Events\PendaftaranStatusUpdated;

class CheckPendaftaranStatus extends Command
{
    protected $signature = 'app:check-pendaftaran-status';
    protected $description = 'Check and update pendaftaran status based on payment and timeout';

    public function handle(): int
    {
        $this->updateStatusBasedOnPayments();
        $this->cancelOverduePendaftaran();
        return Command::SUCCESS;
    }

    private function updateStatusBasedOnPayments(): void
    {
        $overdue = Pendaftaran::where('status', 'menunggu_pembayaran')
            ->whereHas('pembayaran', fn ($q) => $q->where('status', 'pending'))
            ->get();

        foreach ($overdue as $pendaftaran) {
            $allPaymentsPaid = $pendaftaran->pembayaran()
                ->whereIn('tipe', ['dp', 'lunas'])
                ->where('status', 'berhasil')
                ->count() === $pendaftaran->pembayaran()->count();

            if ($allPaymentsPaid) {
                $oldStatus = $pendaftaran->status;
                $pendaftaran->update(['status' => 'terdaftar']);
                event(new PendaftaranStatusUpdated($pendaftaran, $oldStatus, 'terdaftar'));
                Log::info('Pendaftaran #' . $pendaftaran->id . ' updated to terdaftar');
            }
        }
    }

    private function cancelOverduePendaftaran(): void
    {
        $overdue = Pendaftaran::where('status', 'menunggu_pembayaran')
            ->where('created_at', '<=', now()->subHours(24))
            ->get();

        foreach ($overdue as $pendaftaran) {
            $oldStatus = $pendaftaran->status;
            $pendaftaran->update(['status' => 'dibatalkan_timeout']);
            $pendaftaran->kelas->decrement('kuota_terisi');
            event(new PendaftaranStatusUpdated($pendaftaran, $oldStatus, 'dibatalkan_timeout'));
            Log::info('Pendaftaran #' . $pendaftaran->id . ' cancelled due to timeout');
        }
    }
}