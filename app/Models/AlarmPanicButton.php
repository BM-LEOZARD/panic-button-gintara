<?php

namespace App\Models;

use App\Models\DokumenFoto;
use App\Models\LokasiPanicButton;
use App\Models\PanicButton;
use App\Models\Pelanggan;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AlarmPanicButton extends Model
{
    protected $table = 'alarm_panic_button';

    protected $fillable = [
        'panic_button_id',
        'lokasi_panic_button_id',
        'pelanggan_id',
        'user_id',
        'ditangani_oleh',
        'status',
        'waktu_trigger',
        'waktu_selesai',
        'keterangan',
    ];

        protected $casts = [
        'waktu_trigger'  => 'datetime',
        'waktu_selesai'  => 'datetime',
    ];

    public function panicButton()
    {
        return $this->belongsTo(PanicButton::class, 'panic_button_id');
    }

    public function lokasi()
    {
        return $this->belongsTo(LokasiPanicButton::class, 'lokasi_panic_button_id');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function dokumenFoto()
    {
        return $this->hasMany(DokumenFoto::class, 'alarm_panic_button_id');
    }
}
