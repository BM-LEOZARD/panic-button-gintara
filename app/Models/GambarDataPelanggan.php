<?php

namespace App\Models;

use App\Models\Pelanggan;
use Illuminate\Database\Eloquent\Model;

class GambarDataPelanggan extends Model
{
    protected $table = 'gambar_data_pelanggan';

    protected $fillable = [
        'pelanggan_id', 'foto_ktp',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }
}
