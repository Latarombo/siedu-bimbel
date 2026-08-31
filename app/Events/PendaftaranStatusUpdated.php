<?php

namespace App\Events;

use App\Models\Pendaftaran;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PendaftaranStatusUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pendaftaran;

    public $oldStatus;

    public $newStatus;

    public function __construct(Pendaftaran $pendaftaran, string $oldStatus, string $newStatus)
    {
        $this->pendaftaran = $pendaftaran;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }
}
