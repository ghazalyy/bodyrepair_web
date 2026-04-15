<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Keuangan</title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 8pt; color: #1a1a2e; background: #fff; }

/* KOP */
.kop { width: 100%; border-bottom: 3px double #1e293b; padding-bottom: 8px; margin-bottom: 6px; }
.kop table { width: 100%; border-collapse: collapse; }
.kop td { padding: 0 4px; border: none; background: transparent; }
.logo-box { width: 52px; height: 52px; background: #1e293b; border-radius: 6px; text-align: center; line-height: 52px; color: #fff; font-size: 16pt; font-weight: bold; }
.kop-nama { font-size: 13pt; font-weight: bold; color: #1e293b; }
.kop-sub  { font-size: 7.5pt; color: #475569; margin-top: 1px; }
.doc-title { font-size: 11pt; font-weight: bold; color: #1e293b; text-transform: uppercase; letter-spacing: 1px; }
.doc-nomor { font-size: 7pt; color: #64748b; margin-top: 2px; }

/* INFO BAR */
.info-bar { background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 4px; padding: 5px 10px; margin-bottom: 10px; font-size: 7.5pt; color: #475569; }
.info-bar table { width: 100%; border-collapse: collapse; }
.info-bar td { padding: 1px 4px; border: none; background: transparent; }
.lbl { font-weight: bold; color: #1e293b; width: 110px; }

/* SECTION TITLE */
.section-title { font-size: 8.5pt; font-weight: bold; color: #1e293b; background: #e2e8f0; padding: 4px 8px; border-left: 4px solid #334155; margin: 10px 0 4px 0; }

/* JURNAL TABLE */
table.ledger { width: 100%; border-collapse: collapse; font-size: 7.5pt; margin-bottom: 6px; }
table.ledger thead tr.hdr1 th { background: #1e293b; color: #f8fafc; padding: 5px 6px; border: 1px solid #334155; text-align: center; font-weight: bold; }
table.ledger thead tr.hdr2 th { background: #334155; color: #cbd5e1; padding: 3px 6px; font-size: 7pt; border: 1px solid #334155; }
table.ledger tbody tr td { padding: 3px 6px; border: 1px solid #e2e8f0; vertical-align: top; }
table.ledger tbody tr.odd  td { background: #ffffff; }
table.ledger tbody tr.even td { background: #f8fafc; }
table.ledger tbody tr.subtotal td { background: #dbeafe; font-weight: bold; border-top: 1.5px solid #93c5fd; border-bottom: 1.5px solid #93c5fd; }
table.ledger tbody tr.gtotal td { background: #1e293b; color: #f8fafc; font-weight: bold; border-top: 2px solid #0f172a; font-size: 8pt; }

/* ALIGNMENT */
.tc { text-align: center; }
.tr { text-align: right; }
.tl { text-align: left; }
.mono { font-family: DejaVu Sans Mono, monospace; }
.cr { color: #059669; font-weight: 600; }
.db { color: #dc2626; font-weight: 600; }
.bpos { color: #1d4ed8; font-weight: bold; }
.bneg { color: #b91c1c; font-weight: bold; }

/* SUMMARY */
.sum-wrap { width: 100%; margin-top: 10px; }
.sum-wrap table.outer { width: 100%; border-collapse: collapse; }
.sum-wrap table.outer td { vertical-align: top; padding: 0; border: none; background: transparent; }

table.sum { width: 100%; border-collapse: collapse; font-size: 8pt; }
table.sum th { background: #334155; color: #f1f5f9; padding: 5px 8px; text-align: left; font-size: 8pt; }
table.sum td { padding: 4px 8px; border-bottom: 1px solid #e2e8f0; }
table.sum tr.even td { background: #f8fafc; }
table.sum tr.sub td { background: #e0f2fe; font-weight: bold; border-top: 1.5px solid #7dd3fc; }
table.sum tr.grand td { background: #1e293b; color: #f8fafc; font-weight: bold; font-size: 8.5pt; }

/* RESULT BOX */
.rbox { border: 2px solid #1e293b; border-radius: 4px; overflow: hidden; }
.rbox .rhdr { background: #1e293b; color: #f8fafc; text-align: center; padding: 6px; font-weight: bold; font-size: 8.5pt; }
table.rtbl { width: 100%; border-collapse: collapse; font-size: 8pt; }
table.rtbl td { padding: 5px 10px; border-bottom: 1px solid #e2e8f0; }
table.rtbl tr.rfinal td { background: #ecfdf5; font-weight: bold; font-size: 9pt; color: #059669; border-top: 2px solid #059669; }
table.rtbl tr.rloss td  { background: #fef2f2; color: #dc2626; border-top: 2px solid #dc2626; }

/* TTD */
.ttd-wrap { width: 100%; margin-top: 20px; }
.ttd-wrap table { width: 100%; border-collapse: collapse; }
.ttd-wrap td { text-align: center; padding: 0 10px; vertical-align: bottom; border: none; background: transparent; }
.ttd-label { font-size: 7.5pt; color: #475569; margin-bottom: 40px; }
.ttd-line { border-top: 1px solid #334155; padding-top: 4px; font-size: 7.5pt; font-weight: bold; color: #1e293b; }
.ttd-jab { font-size: 6.5pt; color: #64748b; }

/* FOOTER */
.doc-footer { margin-top: 16px; border-top: 1px solid #cbd5e1; padding-top: 5px; text-align: center; font-size: 6.5pt; color: #94a3b8; }
</style>
</head>
<body>

@php
    use Carbon\Carbon;
    $dari_fmt   = Carbon::parse($dari)->format('d/m/Y');
    $sampai_fmt = Carbon::parse($sampai)->format('d/m/Y');
    $dari_panjang   = Carbon::parse($dari)->translatedFormat('j F Y');
    $sampai_panjang = Carbon::parse($sampai)->translatedFormat('j F Y');
    $nomor_dok  = 'LK/' . Carbon::parse($dari)->format('Y/m') . '/001';
    $cetak_jam  = now()->format('d/m/Y H:i');
    $jml_entri  = $dataKeuangans->count();
@endphp

{{-- KOP SURAT --}}
<div class="kop">
    <table>
        <tr>
            <td style="width:58px; vertical-align:middle;">
                <div class="logo-box">BR</div>
            </td>
            <td style="vertical-align:middle; padding-left:10px;">
                <div class="kop-nama">Garasi Kampoeng X HGMP</div>
                <div class="kop-sub">Sistem Administrasi Keuangan Bengkel</div>
            </td>
            <td style="vertical-align:middle; text-align:right; width:200px;">
                <div class="doc-title">Laporan Keuangan</div>
                <div class="doc-nomor">No: {{ $nomor_dok }}</div>
                <div class="doc-nomor">Dicetak: {{ $cetak_jam }}</div>
            </td>
        </tr>
    </table>
</div>

{{-- INFO PERIODE --}}
<div class="info-bar">
    <table>
        <tr>
            <td class="lbl">Periode Laporan</td>
            <td>: {{ $dari_panjang }} s/d {{ $sampai_panjang }}</td>
            <td style="text-align:right; color:#1e293b; font-weight:bold;">Mata Uang: IDR (Rupiah Indonesia)</td>
        </tr>
        <tr>
            <td class="lbl">Jenis Laporan</td>
            <td>: Jurnal Umum Keuangan</td>
            <td style="text-align:right; color:#475569;">Total Transaksi: {{ $jml_entri }} entri</td>
        </tr>
    </table>
</div>

{{-- JURNAL UMUM --}}
<div class="section-title">I. JURNAL UMUM &mdash; BUKU KAS</div>

@php
    $no             = 1;
    $saldo          = 0;
    $currentMonth   = null;
    $monthIn        = 0;
    $monthOut       = 0;
    $rowCount       = 0;
@endphp

<table class="ledger">
    <thead>
        <tr class="hdr1">
            <th rowspan="2" style="width:20px;">No</th>
            <th rowspan="2" style="width:60px;">Tanggal</th>
            <th rowspan="2" style="width:58px;">Ref / Kode</th>
            <th rowspan="2">Keterangan Transaksi</th>
            <th colspan="2" style="border-bottom:1px solid #475569;">Debit / Kredit (Rp)</th>
            <th rowspan="2" style="width:88px;">Saldo Berjalan (Rp)</th>
        </tr>
        <tr class="hdr2">
            <th style="width:88px;">Pemasukan (Kredit)</th>
            <th style="width:88px;">Pengeluaran (Debit)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($dataKeuangans as $d)
            @php
                $bulan = $d->tanggal->format('Y-m');
            @endphp

            @if($currentMonth !== null && $currentMonth !== $bulan)
                @php
                    $selisihBulan = $monthIn - $monthOut;
                    $selisihLabel = $selisihBulan >= 0 ? number_format($selisihBulan,0,',','.') : '('.number_format(abs($selisihBulan),0,',','.').')';
                    $selisihClass = $selisihBulan >= 0 ? 'bpos' : 'bneg';
                    $nmBulan = Carbon::parse($currentMonth)->translatedFormat('F Y');
                @endphp
                <tr class="subtotal">
                    <td colspan="4" class="tr" style="font-size:7.5pt;">Sub-Total {{ $nmBulan }}</td>
                    <td class="tr mono cr">{{ number_format($monthIn,0,',','.') }}</td>
                    <td class="tr mono db">{{ number_format($monthOut,0,',','.') }}</td>
                    <td class="tr mono {{ $selisihClass }}">{{ $selisihLabel }}</td>
                </tr>
                @php $monthIn = 0; $monthOut = 0; @endphp
            @endif

            @php
                $currentMonth = $bulan;
                $isPemasukan  = $d->jenis === 'pemasukan';
                $saldo       += $isPemasukan ? $d->jumlah : -$d->jumlah;
                if ($isPemasukan) { $monthIn  += $d->jumlah; }
                else              { $monthOut += $d->jumlah; }
                $ref      = $d->id_transaksi ? 'TRX-'.$d->id_transaksi : 'KAS-'.str_pad($d->id_keuangan,4,'0',STR_PAD_LEFT);
                $rowClass = ($rowCount % 2 === 0) ? 'even' : 'odd';
                $rowCount++;
                $saldoLabel = $saldo >= 0 ? number_format($saldo,0,',','.') : '('.number_format(abs($saldo),0,',','.').')';
                $saldoClass = $saldo >= 0 ? 'bpos' : 'bneg';
            @endphp
            <tr class="{{ $rowClass }}">
                <td class="tc" style="color:#94a3b8;">{{ $no++ }}</td>
                <td class="tc">{{ $d->tanggal->format('d/m/Y') }}</td>
                <td class="tc" style="font-size:6.5pt; color:#4f46e5;">{{ $ref }}</td>
                <td>
                    {{ $d->keterangan }}
                    @if($d->id_transaksi)
                        <span style="font-size:6pt; color:#64748b; font-style:italic;">[Auto]</span>
                    @endif
                </td>
                <td class="tr mono {{ $isPemasukan ? 'cr' : '' }}">
                    @if($isPemasukan){{ number_format($d->jumlah,0,',','.') }}@else &mdash; @endif
                </td>
                <td class="tr mono {{ !$isPemasukan ? 'db' : '' }}">
                    @if(!$isPemasukan){{ number_format($d->jumlah,0,',','.') }}@else &mdash; @endif
                </td>
                <td class="tr mono {{ $saldoClass }}">{{ $saldoLabel }}</td>
            </tr>
        @endforeach

        @if($currentMonth !== null)
            @php
                $selisihBulan = $monthIn - $monthOut;
                $selisihLabel = $selisihBulan >= 0 ? number_format($selisihBulan,0,',','.') : '('.number_format(abs($selisihBulan),0,',','.').')';
                $selisihClass = $selisihBulan >= 0 ? 'bpos' : 'bneg';
                $nmBulan = Carbon::parse($currentMonth)->translatedFormat('F Y');
            @endphp
            <tr class="subtotal">
                <td colspan="4" class="tr" style="font-size:7.5pt;">Sub-Total {{ $nmBulan }}</td>
                <td class="tr mono cr">{{ number_format($monthIn,0,',','.') }}</td>
                <td class="tr mono db">{{ number_format($monthOut,0,',','.') }}</td>
                <td class="tr mono {{ $selisihClass }}">{{ $selisihLabel }}</td>
            </tr>
        @endif

        @php
            $gtotalLaba = $totalLaba >= 0 ? number_format($totalLaba,0,',','.') : '('.number_format(abs($totalLaba),0,',','.').')';
        @endphp
        <tr class="gtotal">
            <td colspan="4" class="tr" style="letter-spacing:.5px;">GRAND TOTAL PERIODE</td>
            <td class="tr mono">{{ number_format($totalPemasukan,0,',','.') }}</td>
            <td class="tr mono">{{ number_format($totalPengeluaran,0,',','.') }}</td>
            <td class="tr mono">{{ $gtotalLaba }}</td>
        </tr>
    </tbody>
</table>

{{-- RINGKASAN --}}
<div class="section-title">II. RINGKASAN LAPORAN KEUANGAN</div>

@php
    $rasio  = $totalPemasukan > 0 ? number_format(($totalPengeluaran/$totalPemasukan)*100,1) : 'N/A';
    $margin = $totalPemasukan > 0 ? number_format(($totalLaba/$totalPemasukan)*100,1) : 'N/A';
    $laba_label = $totalLaba >= 0 ? 'Rp '.number_format($totalLaba,0,',','.') : '(Rp '.number_format(abs($totalLaba),0,',','.').')';
    $is_laba = $totalLaba >= 0;
@endphp

<div class="sum-wrap">
    <table class="outer">
        <tr>
            {{-- Kiri: Rincian --}}
            <td style="width:56%; padding-right:8px;">
                <table class="sum">
                    <thead>
                        <tr><th colspan="2">RINCIAN PEMASUKAN &amp; PENGELUARAN</th></tr>
                    </thead>
                    <tbody>
                        <tr class="odd">
                            <td>Total Pemasukan (Pendapatan)</td>
                            <td class="tr mono cr">Rp {{ number_format($totalPemasukan,0,',','.') }}</td>
                        </tr>
                        <tr class="even">
                            <td>Total Pengeluaran (Biaya Operasional)</td>
                            <td class="tr mono db">Rp {{ number_format($totalPengeluaran,0,',','.') }}</td>
                        </tr>
                        <tr class="sub">
                            <td>Selisih (Laba / Rugi Kotor)</td>
                            <td class="tr mono {{ $is_laba ? 'cr' : 'db' }}">{{ $laba_label }}</td>
                        </tr>
                        <tr class="odd">
                            <td style="font-size:7pt; color:#64748b;">Rasio Pengeluaran / Pemasukan</td>
                            <td class="tr" style="font-size:7pt; color:#64748b;">{{ $rasio }}%</td>
                        </tr>
                        <tr class="even">
                            <td style="font-size:7pt; color:#64748b;">Margin Laba Bersih</td>
                            <td class="tr" style="font-size:7pt; color:#64748b;">{{ $margin }}%</td>
                        </tr>
                    </tbody>
                </table>
            </td>

            {{-- Kanan: Kotak Laba/Rugi --}}
            <td style="width:44%; vertical-align:top;">
                <div class="rbox">
                    <div class="rhdr">PERHITUNGAN LABA / RUGI BERSIH</div>
                    <table class="rtbl">
                        <tr>
                            <td>Total Pendapatan</td>
                            <td class="tr mono cr">Rp {{ number_format($totalPemasukan,0,',','.') }}</td>
                        </tr>
                        <tr>
                            <td>(-) Total Biaya / Pengeluaran</td>
                            <td class="tr mono db">Rp {{ number_format($totalPengeluaran,0,',','.') }}</td>
                        </tr>
                        <tr class="{{ $is_laba ? 'rfinal' : 'rfinal rloss' }}">
                            <td>{{ $is_laba ? 'LABA BERSIH' : 'RUGI BERSIH' }}</td>
                            <td class="tr mono">{{ $laba_label }}</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
</div>

{{-- TANDA TANGAN --}}
<div class="ttd-wrap">
    <table>
        <tr>
            <td>
                <div class="ttd-label">Dibuat oleh,</div>
                <div class="ttd-line">___________________</div>
                <div class="ttd-jab">Kasir / Staff Keuangan</div>
            </td>
            <td>
                <div class="ttd-label">Diperiksa oleh,</div>
                <div class="ttd-line">___________________</div>
                <div class="ttd-jab">Supervisor / Manajer</div>
            </td>
            <td>
                <div class="ttd-label">Disetujui oleh,</div>
                <div class="ttd-line">___________________</div>
                <div class="ttd-jab">Pemilik</div>
            </td>
        </tr>
    </table>
</div>

{{-- FOOTER --}}
<div class="doc-footer">
    Dokumen ini digenerate otomatis oleh Sistem Administrasi Body Repair Bengkel
    | Periode: {{ $dari_fmt }} s/d {{ $sampai_fmt }}
    | Halaman ini sah tanpa tanda tangan basah apabila telah terverifikasi secara digital.
</div>

</body>
</html>
