<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaksi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table      = 'transaksis';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'id_pelanggan',
        'id_user',
        'no_nota',
        'tanggal',
        'plat_nomor',
        'merk_kendaraan',
        'total_bayar',
        'dp',
        'sisa_bayar',
        'metode_pembayaran',
        'status_transaksi',
        'catatan',
    ];

    protected $casts = [
        'tanggal'    => 'date',
        'total_bayar' => 'decimal:2',
        'dp'         => 'decimal:2',
        'sisa_bayar' => 'decimal:2',
    ];

    // Relasi: Transaksi milik seorang pelanggan
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    // Relasi: Transaksi diproses oleh seorang user/kasir
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relasi: Satu transaksi punya banyak detail layanan
    public function details()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id_transaksi');
    }

    // Relasi: Satu transaksi bisa punya satu data keuangan
    public function dataKeuangan()
    {
        return $this->hasOne(DataKeuangan::class, 'id_transaksi', 'id_transaksi');
    }

    // Accessor: format total rupiah
    public function getTotalFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->total_bayar, 0, ',', '.');
    }

    // Accessor: badge status
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status_transaksi) {
            'Lunas'   => '<span class="badge bg-success">Lunas</span>',
            'DP'      => '<span class="badge bg-warning text-dark">DP</span>',
            'Pending' => '<span class="badge bg-secondary">Pending</span>',
            default   => '<span class="badge bg-secondary">' . $this->status_transaksi . '</span>',
        };
    }

    // Generate nomor nota otomatis
    public static function generateNoNota(): string
    {
        $prefix = 'NOTA-' . date('Ymd');
        $last   = self::withTrashed()->where('no_nota', 'like', $prefix . '%')->latest('id_transaksi')->first();
        $seq    = $last ? ((int) substr($last->no_nota, -4)) + 1 : 1;
        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    protected static function booted()
    {
        static::deleting(function ($transaksi) {
            // Hapus data keuangan terkait saat transaksi dihapus
            $transaksi->dataKeuangan()->delete();
        });

        static::updated(function ($transaksi) {
            // Sinkronisasi data keuangan jika ada perubahan status atau dp
            if ($transaksi->wasChanged(['status_transaksi', 'dp', 'total_bayar', 'tanggal'])) {
                $jmlPemasukan = ($transaksi->status_transaksi === 'Lunas') ? (float) $transaksi->total_bayar : (float) $transaksi->dp;
                
                // Cari data keuangan secara manual jika relasi tidak dimuat
                $dataKeuangan = \App\Models\DataKeuangan::where('id_transaksi', $transaksi->id_transaksi)->first();

                if ($dataKeuangan) {
                    if ($jmlPemasukan > 0) {
                        $dataKeuangan->update([
                            'jumlah'     => $jmlPemasukan,
                            'tanggal'    => $transaksi->tanggal,
                            'keterangan' => 'Pembayaran Nota: ' . $transaksi->no_nota . ' (' . $transaksi->status_transaksi . ')',
                        ]);
                    } else {
                        $dataKeuangan->delete();
                    }
                } elseif ($jmlPemasukan > 0) {
                    \App\Models\DataKeuangan::create([
                        'id_user'      => \Illuminate\Support\Facades\Auth::id() ?? $transaksi->id_user,
                        'tanggal'      => $transaksi->tanggal,
                        'jenis'        => 'pemasukan',
                        'jumlah'       => $jmlPemasukan,
                        'keterangan'   => 'Pembayaran Nota: ' . $transaksi->no_nota . ' (' . $transaksi->status_transaksi . ')',
                        'id_transaksi' => $transaksi->id_transaksi,
                    ]);
                }
            }
        });
    }
}

