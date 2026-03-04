<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    protected $table = 'otp_code';

    protected $fillable = [
        'email',
        'otp',
        'expired_at',
        'used',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'used'       => 'boolean',
    ];

    /**
     * Cek apakah OTP masih valid (belum expired dan belum digunakan)
     */
    public function isValid(): bool
    {
        return !$this->used && $this->expires_at->isFuture();
    }
}
