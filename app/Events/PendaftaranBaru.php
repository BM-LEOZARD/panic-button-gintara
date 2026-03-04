<?php

namespace App\Events;

use App\Models\Pendaftaran;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PendaftaranBaru
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Pendaftaran $pendaftaran)
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
            new PrivateChannel('superadmin'),
        ];
    }
    public function broadcastAs(): string
    {
        return 'pendaftaran.baru';
    }

    public function broadcastWith(): array
    {
        return [
            'nama'    => $this->pendaftaran->name,
            'wilayah' => $this->pendaftaran->wilayah->nama,
            'no_hp'   => $this->pendaftaran->no_hp,
        ];
    }
}
