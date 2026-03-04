<?php

namespace App\Events;

use App\Models\TugasAdmin;
// use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
// use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
// use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TugasAdminDitugaskan
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public TugasAdmin $tugasAdmin)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin.' . $this->tugasAdmin->user_id),
        ];
    }
    public function broadcastAs(): string
    {
        return 'tugas.ditugaskan';
    }

    public function broadcastWith(): array
    {
        return [
            'wilayah_nama'      => $this->tugasAdmin->wilayah->nama,
            'wilayah_kode'      => $this->tugasAdmin->wilayah->kode_wilayah,
        ];
    }
}
