<?php

namespace App\Models;

use App\Models\AlarmPanicButton;
use App\Models\GambarDataPelanggan;
use App\Models\PanicButton;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';

    protected $fillable = [
        'user_id',
        'nik',
        'ttl',
        'alamat',
        'RT',
        'RW',
        'desa',
        'kecamatan',
        'kelurahan',
    ];

    // Pelanggan milik satu user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Pelanggan punya satu panic button
    public function panicButton()
    {
        return $this->hasOne(PanicButton::class, 'pelanggan_id');
    }

    // Pelanggan punya satu foto KTP
    public function gambar()
    {
        return $this->hasOne(GambarDataPelanggan::class, 'pelanggan_id');
    }

    // Riwayat alarm pelanggan
    public function alarm()
    {
        return $this->hasMany(AlarmPanicButton::class, 'pelanggan_id');
    }
}
