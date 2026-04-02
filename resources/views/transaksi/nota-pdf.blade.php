<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota PDF - {{ $transaksi->no_nota }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size:10pt; color:#333; margin:0; padding:0; }
        .header { background:#0f172a; color:#fff; padding:15px 20px; text-align:center; }
        .header h2 { margin:0; font-size:13pt; }
        .header p { margin:2px 0; font-size:8pt; color:#94a3b8; }
        .content { padding:15px 20px; }
        .nota-no { text-align:center; margin:8px 0; font-size:11pt; font-weight:bold; color:#4f46e5; letter-spacing:.05em; }
        table.info { width:100%; font-size:9pt; margin-bottom:12px; }
        table.info td { padding:3px 0; }
        table.info td:first-child { color:#64748b; width:35%; }
        table.layanan { width:100%; border-collapse:collapse; font-size:9pt; }
        table.layanan th { background:#f1f5f9; padding:5px 8px; text-align:left; border-bottom:1px solid #e2e8f0; }
        table.layanan td { padding:4px 8px; border-bottom:1px solid #f1f5f9; }
        .total-box { background:#f8fafc; border:1px solid #e2e8f0; border-radius:4px; padding:8px 12px; margin-top:10px; }
        .total-row { display:flex; justify-content:space-between; font-size:9pt; margin:2px 0; }
        .total-row.main { font-size:12pt; font-weight:bold; color:#4f46e5; border-top:1px dashed #e2e8f0; padding-top:6px; margin-top:4px; }
        .footer { text-align:center; font-size:8pt; color:#94a3b8; padding:10px 20px; border-top:1px dashed #e2e8f0; }
        .status { display:inline-block; padding:2px 8px; border-radius:100px; font-size:8pt; font-weight:bold; border:1.5px solid; }
        .lunas   { color:#059669; border-color:#059669; }
        .dp      { color:#d97706; border-color:#d97706; }
        .pending { color:#64748b; border-color:#64748b; }
    </style>
</head>
<body>
<div class="header">
    <h2>Body Repair Admin</h2>
    <p>Nota Pembayaran Jasa Perbaikan Kendaraan</p>
</div>
<div class="content">
    <div class="nota-no">{{ $transaksi->no_nota }}</div>

    <table class="info">
        <tr><td>Tanggal</td><td>: {{ $transaksi->tanggal->format('d/m/Y') }}</td></tr>
        <tr><td>Pelanggan</td><td>: {{ $transaksi->pelanggan->nama ?? '-' }}</td></tr>
        @if($transaksi->plat_nomor)
        <tr><td>Kendaraan</td><td>: {{ $transaksi->plat_nomor }} {{ $transaksi->merk_kendaraan ? '('.$transaksi->merk_kendaraan.')':'' }}</td></tr>
        @endif
        <tr><td>Kasir</td><td>: {{ $transaksi->user->nama ?? '-' }}</td></tr>
        <tr><td>Metode Bayar</td><td>: {{ ucfirst($transaksi->metode_pembayaran) }}</td></tr>
        <tr>
            <td>Status</td>
            <td>: <span class="status {{ strtolower($transaksi->status_transaksi) }}">{{ $transaksi->status_transaksi }}</span></td>
        </tr>
    </table>

    <hr style="border:none;border-top:1px dashed #e2e8f0;margin:10px 0;">

    <table class="layanan">
        <thead>
            <tr><th>Layanan</th><th>Qty</th><th>Harga</th><th>Subtotal</th></tr>
        </thead>
        <tbody>
            @foreach($transaksi->details as $d)
            <tr>
                <td>{{ $d->layanan->nama_layanan ?? '-' }}</td>
                <td>{{ $d->jumlah }}</td>
                <td>Rp {{ number_format($d->harga_satuan,0,',','.') }}</td>
                <td>Rp {{ number_format($d->subtotal,0,',','.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        @if($transaksi->dp > 0)
        <div class="total-row">
            <span>DP Dibayar</span><span>Rp {{ number_format($transaksi->dp,0,',','.') }}</span>
        </div>
        <div class="total-row">
            <span>Sisa Bayar</span><span>Rp {{ number_format($transaksi->sisa_bayar,0,',','.') }}</span>
        </div>
        @endif
        <div class="total-row main">
            <span>TOTAL</span><span>Rp {{ number_format($transaksi->total_bayar,0,',','.') }}</span>
        </div>
    </div>

    @if($transaksi->catatan)
    <p style="font-size:8pt;color:#64748b;margin-top:8px;">Catatan: {{ $transaksi->catatan }}</p>
    @endif
</div>
<div class="footer">
    Terima kasih atas kepercayaan Anda!<br>
    Dicetak: {{ now()->format('d/m/Y H:i') }}
</div>
</body>
</html>
