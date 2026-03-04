<?php

namespace App\Events;

use App\Models\AlarmPanicButton;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AlarmDiproses implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public AlarmPanicButton $alarm)
    {
        $this->alarm->loadMissing([
            'pelanggan.user',
            'panicButton.wilayah',
        ]);
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
            new PrivateChannel('pelanggan.' . $this->alarm->pelanggan_id),
        ];
    }
    public function broadcastAs(): string
    {
        return 'alarm.diproses';
    }

    public function broadcastWith(): array
    {
        return [
            'alarm_id'       => $this->alarm->id,
            'pelanggan_nama' => $this->alarm->pelanggan->user->name,
            'admin_nama'     => $this->alarm->ditangani_oleh,
            'wilayah_nama'   => $this->alarm->panicButton->wilayah->nama,
        ];
    }
}
