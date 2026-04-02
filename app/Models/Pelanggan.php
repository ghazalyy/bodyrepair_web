<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pelanggan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table      = 'pelanggans';
    protected $primaryKey = 'id_pelanggan';

    protected $fillable = [
        'nama',
        'alamat',
        'no_telepon',
    ];

    // Relasi: Satu pelanggan bisa punya banyak transaksi
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'id_pelanggan', 'id_pelanggan');
    }

    // Accessor: format nomor telepon untuk tampilan
    public function getNoTeleponFormatAttribute(): string
    {
        return $this->no_telepon ?? '-';
    }
}
