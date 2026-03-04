<?php

namespace App\Models;

use App\Models\AlarmPanicButton;
use Illuminate\Database\Eloquent\Model;

class DokumenFoto extends Model
{
    protected $table = 'dokumen_foto';

    protected $fillable = [
        'alarm_panic_button_id',
        'foto_dokumentasi',
        'keterangan',
    ];

    public function alarm()
    {
        return $this->belongsTo(AlarmPanicButton::class, 'alarm_panic_button_id');
    }
}
