<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Pelanggan;
use App\Models\TugasAdmin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'role',
        'jenis_kelamin',
        'no_hp',
        'email',
        'password',
        'otp_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'otp_verified_at'   => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Cek apakah EndUser sudah pernah verifikasi OTP sebelumnya.
     * Admin & SuperAdmin selalu dianggap sudah verifikasi (tidak perlu OTP).
     */
    public function hasVerifiedOtp(): bool
    {
        if ($this->role !== 'EndUser') {
            return true;
        }

        return $this->otp_verified_at !== null;
    }

    // Satu user punya satu data pelanggan
    public function pelanggan()
    {
        return $this->hasOne(Pelanggan::class, 'user_id');
    }

    // Admin punya tugas wilayah
    public function tugasAdmin()
    {
        return $this->hasMany(TugasAdmin::class, 'user_id');
    }

    // Admin yang menangani alarm
    public function alarmDitangani()
    {
        return $this->hasMany(AlarmPanicButton::class, 'user_id');
    }
}
