<?php

namespace App\Models;

use App\Models\AlarmPanicButton;
use App\Models\LokasiPanicButton;
use App\Models\Pelanggan;
use App\Models\WilayahCover;
use Illuminate\Database\Eloquent\Model;

class PanicButton extends Model
{
    protected $table = 'panic_button';

    protected $fillable = [
        'pelanggan_id',
        'wilayah_id',
        'DisID',
        'GUID',
        'GetBlockID',
        'GetNumber',
        'state',
        'timestamp',
    ];

    protected $casts = [
        'timestamp' => 'timestamp',
    ];

    // Panic button milik satu pelanggan
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    // Panic button berada di satu wilayah
    public function wilayah()
    {
        return $this->belongsTo(WilayahCover::class, 'wilayah_id');
    }

    // Panic button punya satu lokasi
    public function lokasi()
    {
        return $this->hasOne(LokasiPanicButton::class, 'panic_button_id');
    }

    // Riwayat alarm dari panic button ini
    public function alarm()
    {
        return $this->hasMany(AlarmPanicButton::class, 'panic_button_id');
    }
}
