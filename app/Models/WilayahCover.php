<?php

namespace App\Models;

use App\Models\PanicButton;
use App\Models\TugasAdmin;
use Illuminate\Database\Eloquent\Model;

class WilayahCover extends Model
{
    protected $table = 'wilayah_cover';

    protected $fillable = [
        'kode_wilayah',
        'nama',
        'latitude',
        'longtitude',
        'radius_meter',
        'alamat',
    ];

    // Wilayah punya banyak panic button
    public function panicButton()
    {
        return $this->hasMany(PanicButton::class, 'wilayah_id');
    }

    // Wilayah punya banyak admin yang bertugas
    public function tugasAdmin()
    {
        return $this->hasMany(TugasAdmin::class, 'wilayah_cover_id');
    }
}
