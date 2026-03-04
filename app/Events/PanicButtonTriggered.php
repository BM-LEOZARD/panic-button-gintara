<?php

namespace App\Events;

use App\Models\AlarmPanicButton;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PanicButtonTriggered implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public AlarmPanicButton $alarm, public int $wilayahId,)
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
            new PrivateChannel('wilayah.' . $this->wilayahId),
            new PrivateChannel('superadmin'),
        ];
    }
    public function broadcastAs(): string
    {
        return 'panic.triggered';
    }

    public function broadcastWith(): array
    {
        return [
            'alarm_id'        => $this->alarm->id,
            'pelanggan_nama'  => $this->alarm->pelanggan->user->name,
            'pelanggan_no_hp' => $this->alarm->pelanggan->user->no_hp,
            'wilayah_nama'    => $this->alarm->panicButton->wilayah->nama,
            'blok'            => $this->alarm->panicButton->GetBlockID,
            'nomor'           => $this->alarm->panicButton->GetNumber,
            'epoch'           => $this->alarm->panicButton->timestamp,
        ];
    }
}
