<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'users';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'username',
        'password',
        'HoTen',
        'VaiTro',
        'email',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Quan hệ với SinhVien
     */
    public function sinhVien()
    {
        return $this->hasOne(SinhVien::class, 'user_id', 'id');
    }

    /**
     * Quan hệ với NhanVien
     */
    public function nhanVien()
    {
        return $this->hasOne(NhanVien::class, 'user_id', 'id');
    }

    /**
     * Quan hệ với GiangVien
     */
    public function giangVien()
    {
        return $this->hasOne(GiangVien::class, 'user_id', 'id');
    }
}
