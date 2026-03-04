<?php

namespace App\Models;

use App\Models\WilayahCover;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    protected $table = 'pendaftaran';

    protected $fillable = [
        'wilayah_cover_id',
        'name',
        'username',
        'no_hp',
        'email',
        'jenis_kelamin',
        'nik',
        'ttl',
        'alamat',
        'RT',
        'RW',
        'GetBlockID',
        'GetNumber',
        'desa',
        'kecamatan',
        'kelurahan',
        'latitude',
        'longtitude',
        'foto_ktp',
        'status',
        'waktu_verifikasi',
        'catatan_penolakan',
    ];

    protected $casts = [
        'waktu_verifikasi' => 'datetime',
    ];

    public function wilayah()
    {
        return $this->belongsTo(WilayahCover::class, 'wilayah_cover_id');
    }

    public function panicButton()
    {
        return $this->belongsTo(PanicButton::class);
    }
}
