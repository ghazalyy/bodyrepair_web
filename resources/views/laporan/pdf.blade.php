<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan {{ $dari }} s/d {{ $sampai }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10pt; color: #333; margin: 0; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 8px; margin-bottom: 12px; }
        .header h2 { margin: 0; font-size: 14pt; }
        .header p  { margin: 2px 0; font-size: 9pt; color: #555; }
        table { width: 100%; border-collapse: collapse; font-size: 9pt; margin-bottom: 12px; }
        th { background: #334155; color: #fff; padding: 5px 8px; text-align: left; }
        td { padding: 4px 8px; border-bottom: 1px solid #eee; }
        .text-right { text-align: right; }
        .pemasukan  { color: #059669; }
        .pengeluaran{ color: #dc2626; }
        .summary { background: #f8fafc; border-radius: 6px; padding: 10px 14px; }
        .summary-row { display: flex; justify-content: space-between; margin: 3px 0; font-size: 10pt; }
        .summary-row.total { font-weight: bold; font-size: 12pt; border-top: 2px solid #334155; padding-top: 6px; margin-top: 6px; }
        .footer { text-align: center; font-size: 8pt; color: #777; margin-top: 20px; }
    </style>
</head>
<body>
<div class="header">
    <h2>LAPORAN KEUANGAN</h2>
    <p>Body Repair Admin — Sistem Administrasi Keuangan Bengkel</p>
    <p>Periode: {{ \Carbon\Carbon::parse($dari)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($sampai)->format('d/m/Y') }}</p>
    <p>Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
</div>

<table>
    <thead>
        <tr><th>Tanggal</th><th>Keterangan</th><th>Jenis</th><th class="text-right">Jumlah</th></tr>
    </thead>
    <tbody>
        @foreach($dataKeuangans as $d)
        <tr>
            <td>{{ $d->tanggal->format('d/m/Y') }}</td>
            <td>{{ $d->keterangan }}</td>
            <td class="{{ $d->jenis }}">{{ ucfirst($d->jenis) }}</td>
            <td class="text-right {{ $d->jenis }}">
                {{ $d->jenis === 'pemasukan' ? '+' : '-' }} Rp {{ number_format($d->jumlah,0,',','.') }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="summary">
    <div class="summary-row">
        <span>Total Pemasukan</span>
        <span class="pemasukan">+ Rp {{ number_format($totalPemasukan,0,',','.') }}</span>
    </div>
    <div class="summary-row">
        <span>Total Pengeluaran</span>
        <span class="pengeluaran">- Rp {{ number_format($totalPengeluaran,0,',','.') }}</span>
    </div>
    <div class="summary-row total">
        <span>{{ $totalLaba >= 0 ? 'LABA BERSIH' : 'RUGI BERSIH' }}</span>
        <span class="{{ $totalLaba >= 0 ? 'pemasukan' : 'pengeluaran' }}">
            Rp {{ number_format(abs($totalLaba),0,',','.') }}
        </span>
    </div>
</div>

<div class="footer">
    Laporan ini digenerate otomatis oleh sistem Body Repair Admin.<br>
    Dokumen ini sah tanpa tanda tangan.
</div>
</body>
</html>
