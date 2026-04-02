@extends('layouts.app')
@section('title', 'Rekap Transaksi')
@section('page-title', 'Rekap Transaksi')
@section('breadcrumb')
    <li class="breadcrumb-item active">Rekap Transaksi</li>
@endsection

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card" style="border-left:4px solid #4f46e5;">
            <div class="stat-icon" style="background:#eff6ff;color:#4f46e5;"><i class="bi bi-cash-stack"></i></div>
            <div class="stat-value" style="font-size:1.1rem;">Rp {{ number_format($totalNilai,0,',','.') }}</div>
            <div class="stat-label">Total Nilai Transaksi</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="border-left:4px solid #10b981;">
            <div class="stat-icon" style="background:#ecfdf5;color:#10b981;"><i class="bi bi-check-circle-fill"></i></div>
            <div class="stat-value">{{ $totalLunas }}</div>
            <div class="stat-label">Transaksi Lunas</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="border-left:4px solid #f59e0b;">
            <div class="stat-icon" style="background:#fffbeb;color:#d97706;"><i class="bi bi-hourglass-split"></i></div>
            <div class="stat-value">{{ $totalPending }}</div>
            <div class="stat-label">Belum Lunas</div>
        </div>
    </div>
</div>

<div class="card-custom">
    <div class="card-header-custom">
        <span><i class="bi bi-journal-text me-2"></i>Rekap Transaksi</span>
    </div>
    <div class="p-3 border-bottom bg-light">
        <form method="GET" class="d-flex gap-2 flex-wrap align-items-end">
            <div>
                <label class="form-label mb-1" style="font-size:.78rem;">Dari</label>
                <input type="date" name="dari" class="form-control form-control-sm" value="{{ $dari }}">
            </div>
            <div>
                <label class="form-label mb-1" style="font-size:.78rem;">Sampai</label>
                <input type="date" name="sampai" class="form-control form-control-sm" value="{{ $sampai }}">
            </div>
            <div class="d-flex gap-1 align-self-end">
                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search"></i> Filter</button>
                <a href="{{ route('laporan.rekap') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x"></i></a>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-custom mb-0">
            <thead>
                <tr><th>#</th><th>No. Nota</th><th>Pelanggan</th><th>Tanggal</th><th>Total</th><th>DP</th><th>Sisa</th><th>Status</th></tr>
            </thead>
            <tbody>
                @forelse($transaksis as $t)
                <tr>
                    <td class="text-muted" style="font-size:.78rem;">{{ $transaksis->firstItem() + $loop->index }}</td>
                    <td><code style="font-size:.78rem;color:#4f46e5;">{{ $t->no_nota }}</code></td>
                    <td>{{ $t->pelanggan->nama ?? '-' }}</td>
                    <td style="font-size:.8rem;">{{ $t->tanggal->format('d/m/Y') }}</td>
                    <td class="fw-semibold">Rp {{ number_format($t->total_bayar,0,',','.') }}</td>
                    <td>{{ $t->dp > 0 ? 'Rp '.number_format($t->dp,0,',','.') : '-' }}</td>
                    <td class="{{ $t->sisa_bayar > 0 ? 'text-danger fw-semibold' : '' }}">
                        {{ $t->sisa_bayar > 0 ? 'Rp '.number_format($t->sisa_bayar,0,',','.') : 'Lunas' }}
                    </td>
                    <td>{!! $t->status_badge !!}</td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">Tidak ada data transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($transaksis->hasPages())
    <div class="p-3 d-flex justify-content-end">{{ $transaksis->links('pagination::bootstrap-5') }}</div>
    @endif
</div>
@endsection
