<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota - {{ $transaksi->no_nota }}</title>
    <style>
        * { box-sizing: border-box; margin:0; padding:0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background:#f0f4f8; display:flex;align-items:center;justify-content:center;min-height:100vh; padding:1rem; }
        .nota-wrapper { background:#fff; width:360px; border-radius:12px; box-shadow:0 8px 30px rgba(0,0,0,.12); overflow:hidden; }
        .nota-header { background:#0f172a; color:#fff; padding:1.5rem 1.25rem 1rem; text-align:center; }
        .nota-header .brand { font-size:1.1rem; font-weight:800; letter-spacing:.02em; }
        .nota-header .brand-sub { font-size:.7rem; color:#94a3b8; margin-top:.1rem; }
        .nota-header .nota-no { margin-top:.75rem; font-size:.75rem; background:rgba(255,255,255,.12); padding:.3rem .8rem; border-radius:100px; display:inline-block; }
        .nota-body { padding:1.25rem; }
        .info-block { margin-bottom:1rem; padding-bottom:1rem; border-bottom:1px dashed #e2e8f0; }
        .info-row { display:flex; justify-content:space-between; font-size:.78rem; margin-bottom:.35rem; }
        .info-row .label { color:#64748b; }
        .info-row .value { font-weight:600; color:#0f172a; text-align:right; max-width:55%; }
        .layanan-title { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#64748b; margin-bottom:.6rem; }
        .layanan-item { display:flex; justify-content:space-between; align-items:flex-start; font-size:.79rem; margin-bottom:.5rem; }
        .layanan-item .nama { color:#1e293b; }
        .layanan-item .qty  { color:#64748b; font-size:.72rem; }
        .layanan-item .sub  { font-weight:600; color:#0f172a; }
        .total-section { background:#f8fafc; border-radius:8px; padding:.85rem 1rem; margin:1rem 0; }
        .total-row { display:flex; justify-content:space-between; font-size:.79rem; margin-bottom:.3rem; }
        .total-row.main { font-size:1rem; font-weight:800; color:#4f46e5; margin-top:.5rem; border-top:1px dashed #e2e8f0; padding-top:.5rem; }
        .status-badge { display:inline-block; padding:.25rem .75rem; border-radius:100px; font-size:.72rem; font-weight:700; border:2px solid; }
        .status-lunas   { color:#059669; border-color:#059669; background:#ecfdf5; }
        .status-dp      { color:#d97706; border-color:#d97706; background:#fffbeb; }
        .status-pending { color:#64748b; border-color:#64748b; background:#f8fafc; }
        .nota-footer { text-align:center; padding:.75rem 1.25rem 1.25rem; }
        .nota-footer p { font-size:.7rem; color:#94a3b8; }
        .nota-footer .ttd { margin-top:1rem; padding-top:2.5rem; border-top:1px solid #e2e8f0; font-size:.78rem; font-weight:600; color:#475569; }
        .print-btn { position:fixed; bottom:2rem; right:2rem; background:#4f46e5; color:#fff; border:none; border-radius:100px; padding:.75rem 1.5rem; font-size:.9rem; font-weight:600; cursor:pointer; box-shadow:0 4px 15px rgba(79,70,229,.4); display:flex;align-items:center;gap:.5rem; }
        .print-btn:hover { background:#3730a3; }
        @media print {
            body { background:#fff;display:block;padding:0; }
            .nota-wrapper { box-shadow:none;border-radius:0;width:100%;max-width:none; }
            .print-btn { display:none !important; }
        }
    </style>
</head>
<body>
<div class="nota-wrapper">
    <div class="nota-header">
        <div class="brand">🚗 Body Repair Admin</div>
        <div class="brand-sub">Sistem Jasa Perbaikan Bodi Kendaraan</div>
        <div class="nota-no">{{ $transaksi->no_nota }}</div>
    </div>

    <div class="nota-body">
        {{-- Info Transaksi --}}
        <div class="info-block">
            <div class="info-row">
                <span class="label">Tanggal</span>
                <span class="value">{{ $transaksi->tanggal->format('d/m/Y') }}</span>
            </div>
            <div class="info-row">
                <span class="label">Pelanggan</span>
                <span class="value">{{ $transaksi->pelanggan->nama ?? '-' }}</span>
            </div>
            @if($transaksi->plat_nomor)
            <div class="info-row">
                <span class="label">Kendaraan</span>
                <span class="value">{{ $transaksi->plat_nomor }} {{ $transaksi->merk_kendaraan ? '(' . $transaksi->merk_kendaraan . ')' : '' }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="label">Kasir</span>
                <span class="value">{{ $transaksi->user->nama ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="label">Metode Bayar</span>
                <span class="value">{{ ucfirst($transaksi->metode_pembayaran) }}</span>
            </div>
            <div class="info-row">
                <span class="label">Status</span>
                <span class="value">
                    <span class="status-badge status-{{ strtolower($transaksi->status_transaksi) }}">
                        {{ $transaksi->status_transaksi }}
                    </span>
                </span>
            </div>
        </div>

        {{-- Daftar Layanan --}}
        <div class="info-block">
            <div class="layanan-title">Rincian Layanan</div>
            @foreach($transaksi->details as $d)
            <div class="layanan-item">
                <div>
                    <div class="nama">{{ $d->layanan->nama_layanan ?? '-' }}</div>
                    <div class="qty">{{ $d->jumlah }} × Rp {{ number_format($d->harga_satuan,0,',','.') }}</div>
                </div>
                <div class="sub">Rp {{ number_format($d->subtotal,0,',','.') }}</div>
            </div>
            @endforeach
        </div>

        {{-- Total --}}
        <div class="total-section">
            <div class="total-row">
                <span>Subtotal</span>
                <span>Rp {{ number_format($transaksi->total_bayar,0,',','.') }}</span>
            </div>
            @if($transaksi->dp > 0)
            <div class="total-row">
                <span>DP Dibayar</span>
                <span>Rp {{ number_format($transaksi->dp,0,',','.') }}</span>
            </div>
            <div class="total-row">
                <span>Sisa Bayar</span>
                <span style="color:#ef4444;">Rp {{ number_format($transaksi->sisa_bayar,0,',','.') }}</span>
            </div>
            @endif
            <div class="total-row main">
                <span>TOTAL</span>
                <span>Rp {{ number_format($transaksi->total_bayar,0,',','.') }}</span>
            </div>
        </div>

        @if($transaksi->catatan)
        <div style="font-size:.75rem;color:#64748b;padding:.5rem;background:#f8fafc;border-radius:6px;margin-bottom:.5rem;">
            <strong>Catatan:</strong> {{ $transaksi->catatan }}
        </div>
        @endif
    </div>

    <div class="nota-footer">
        <p>Terima kasih telah mempercayakan kendaraan Anda kepada kami.<br>Barang yang sudah dibeli tidak dapat dikembalikan.</p>
        <div class="ttd">
            Tanda Tangan Kasir
        </div>
    </div>
</div>

<button class="print-btn" onclick="window.print()">
    <span>🖨️</span> Cetak Nota
</button>
</body>
</html>
