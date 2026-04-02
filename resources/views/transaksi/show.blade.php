@extends('layouts.app')
@section('title', 'Detail Transaksi')
@section('page-title', 'Detail Transaksi')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}" class="text-decoration-none" style="color:#4f46e5">Transaksi</a></li>
    <li class="breadcrumb-item active">{{ $transaksi->no_nota }}</li>
@endsection

@section('content')
<div class="row g-3">
    <div class="col-lg-7">
        <div class="card-custom">
            <div class="card-header-custom">
                <span><i class="bi bi-receipt me-2"></i>Rincian Layanan</span>
                <div class="d-flex gap-2">
                    <a href="{{ route('transaksi.nota', $transaksi->id_transaksi) }}" class="btn btn-sm btn-outline-success" target="_blank" style="font-size:.76rem;">
                        <i class="bi bi-printer"></i> Cetak HTML
                    </a>
                    <a href="{{ route('transaksi.nota.pdf', $transaksi->id_transaksi) }}" class="btn btn-sm btn-outline-danger" style="font-size:.76rem;">
                        <i class="bi bi-filetype-pdf"></i> Download PDF
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-custom mb-0">
                    <thead>
                        <tr><th>#</th><th>Layanan</th><th>Jumlah</th><th>Harga Satuan</th><th>Subtotal</th></tr>
                    </thead>
                    <tbody>
                        @foreach($transaksi->details as $i => $d)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $d->layanan->nama_layanan ?? '-' }}</td>
                            <td>{{ $d->jumlah }}</td>
                            <td>Rp {{ number_format($d->harga_satuan,0,',','.') }}</td>
                            <td class="fw-semibold">Rp {{ number_format($d->subtotal,0,',','.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end fw-bold">TOTAL</td>
                            <td class="fw-bold" style="color:#4f46e5;font-size:1rem;">Rp {{ number_format($transaksi->total_bayar,0,',','.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card-custom mb-3">
            <div class="card-header-custom"><i class="bi bi-info-circle me-2"></i>Info Transaksi</div>
            <div class="p-4">
                <table class="table table-sm mb-0" style="font-size:.83rem;">
                    <tr><td class="text-muted" style="width:45%">No. Nota</td><td><code>{{ $transaksi->no_nota }}</code></td></tr>
                    <tr><td class="text-muted">Tanggal</td><td>{{ $transaksi->tanggal->format('d/m/Y') }}</td></tr>
                    <tr><td class="text-muted">Pelanggan</td><td class="fw-semibold">{{ $transaksi->pelanggan->nama ?? '-' }}</td></tr>
                    <tr><td class="text-muted">No. Telepon</td><td>{{ $transaksi->pelanggan->no_telepon ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Kendaraan</td><td>{{ $transaksi->plat_nomor ?? '-' }} {{ $transaksi->merk_kendaraan ? '('.$transaksi->merk_kendaraan.')':'' }}</td></tr>
                    <tr><td class="text-muted">Kasir</td><td>{{ $transaksi->user->nama ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Metode</td><td>{{ ucfirst($transaksi->metode_pembayaran) }}</td></tr>
                    <tr><td class="text-muted">Status</td><td>{!! $transaksi->status_badge !!}</td></tr>
                    @if($transaksi->dp > 0)
                    <tr><td class="text-muted">DP</td><td>Rp {{ number_format($transaksi->dp,0,',','.') }}</td></tr>
                    <tr><td class="text-muted">Sisa</td><td class="text-danger fw-semibold">Rp {{ number_format($transaksi->sisa_bayar,0,',','.') }}</td></tr>
                    @endif
                    @if($transaksi->catatan)
                    <tr><td class="text-muted">Catatan</td><td>{{ $transaksi->catatan }}</td></tr>
                    @endif
                </table>
            </div>
        </div>

        {{-- Update Status --}}
        @if(auth()->user()->isAdmin())
        <div class="card-custom">
            <div class="card-header-custom"><i class="bi bi-pencil-square me-2"></i>Update Status Pembayaran</div>
            <div class="p-4">
                <form action="{{ route('transaksi.update', $transaksi->id_transaksi) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-2">
                        <label class="form-label">Status</label>
                        <select name="status_transaksi" class="form-select form-select-sm">
                            <option value="Lunas"   {{ $transaksi->status_transaksi==='Lunas'  ?'selected':'' }}>Lunas</option>
                            <option value="DP"      {{ $transaksi->status_transaksi==='DP'     ?'selected':'' }}>DP</option>
                            <option value="Pending" {{ $transaksi->status_transaksi==='Pending'?'selected':'' }}>Pending</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Metode Pembayaran</label>
                        <select name="metode_pembayaran" class="form-select form-select-sm">
                            <option value="tunai"    {{ $transaksi->metode_pembayaran==='tunai'   ?'selected':'' }}>Tunai</option>
                            <option value="transfer" {{ $transaksi->metode_pembayaran==='transfer'?'selected':'' }}>Transfer</option>
                            <option value="qris"     {{ $transaksi->metode_pembayaran==='qris'    ?'selected':'' }}>QRIS</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <input type="text" name="catatan" class="form-control form-control-sm" value="{{ $transaksi->catatan }}">
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary w-100">
                        <i class="bi bi-save2"></i> Update
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
