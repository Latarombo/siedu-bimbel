<?php

namespace App\Listeners;

use App\Events\PendaftaranStatusUpdated;
use Illuminate\Support\Facades\Log;

class NotifyOrangTuaPendaftaranStatus
{
    public function handle(PendaftaranStatusUpdated $event): void
    {
        $pendaftaran = $event->pendaftaran;
        $newStatus = $event->newStatus;

        Log::info("Pendaftaran #{$pendaftaran->id} status changed from {$event->oldStatus} to $newStatus");

        // In a real application, you would send notifications to orang tua
        // For now, we'll just log the event
    }
}
