<?php

namespace App\Models;

use App\Models\PanicButton;
use Illuminate\Database\Eloquent\Model;

class LokasiPanicButton extends Model
{
    protected $table = 'lokasi_panic_button';

    protected $fillable = [
        'panic_button_id',
        'latitude',
        'longtitude',
    ];

    public function panicButton()
    {
        return $this->belongsTo(PanicButton::class, 'panic_button_id');
    }
}
