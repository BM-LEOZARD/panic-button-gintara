<?php

namespace App\Models;

use App\Models\User;
use App\Models\WilayahCover;
use Illuminate\Database\Eloquent\Model;

class TugasAdmin extends Model
{
    protected $table = 'tugas_admin';

    protected $fillable = [
        'user_id',
        'wilayah_cover_id',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function wilayah()
    {
        return $this->belongsTo(WilayahCover::class, 'wilayah_cover_id');
    }
}
