<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Layanan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table      = 'layanans';
    protected $primaryKey = 'id_layanan';

    protected $fillable = [
        'nama_layanan',
        'harga',
        'deskripsi',
        'aktif',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'aktif' => 'boolean',
    ];

    // Relasi: Satu layanan bisa muncul di banyak detail transaksi
    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_layanan', 'id_layanan');
    }

    // Scope: hanya yang aktif
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    // Accessor: format harga rupiah
    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
}
