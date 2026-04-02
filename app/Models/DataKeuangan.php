<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataKeuangan extends Model
{
    use HasFactory;

    protected $table      = 'data_keuangans';
    protected $primaryKey = 'id_keuangan';

    protected $fillable = [
        'id_user',
        'tanggal',
        'jenis',
        'jumlah',
        'keterangan',
        'id_transaksi',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah'  => 'decimal:2',
    ];

    // Relasi: Data keuangan diinput oleh user
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relasi: Data keuangan bisa berasal dari transaksi
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'id_transaksi', 'id_transaksi');
    }

    // Scope: filter pemasukan
    public function scopePemasukan($query)
    {
        return $query->where('jenis', 'pemasukan');
    }

    // Scope: filter pengeluaran
    public function scopePengeluaran($query)
    {
        return $query->where('jenis', 'pengeluaran');
    }

    // Scope: filter by range tanggal
    public function scopeDateRange($query, $from, $to)
    {
        return $query->whereBetween('tanggal', [$from, $to]);
    }

    // Accessor: format jumlah
    public function getJumlahFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }
}
