<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'id_user';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // Relasi: User bisa membuat banyak transaksi
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_user', 'id_user');
    }

    // Relasi: User bisa menginput banyak data keuangan
    public function dataKeuangans()
    {
        return $this->hasMany(DataKeuangan::class, 'id_user', 'id_user');
    }

    // Helper: cek role
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }
}
