@extends('layouts.app')
@section('title', 'Daftar Transaksi')
@section('page-title', 'Daftar Transaksi')
@section('breadcrumb')
    <li class="breadcrumb-item active">Transaksi</li>
@endsection

@section('content')
<div class="card-custom">
    <div class="card-header-custom">
        <span><i class="bi bi-receipt me-2"></i>Data Transaksi</span>
        <a href="{{ route('transaksi.create') }}" class="btn btn-sm btn-primary" style="font-size:.78rem;">
            <i class="bi bi-plus-lg"></i> Tambah Transaksi
        </a>
    </div>

    {{-- Filter --}}
    <div class="p-3 border-bottom bg-light" style="border-radius:0;">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm"
                    placeholder="Cari nota, pelanggan, plat..." value="{{ $keyword }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="Lunas"   {{ $status === 'Lunas'   ? 'selected':'' }}>Lunas</option>
                    <option value="DP"      {{ $status === 'DP'      ? 'selected':'' }}>DP</option>
                    <option value="Pending" {{ $status === 'Pending' ? 'selected':'' }}>Pending</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="dari" class="form-control form-control-sm" value="{{ $dari }}" placeholder="Dari">
            </div>
            <div class="col-md-2">
                <input type="date" name="sampai" class="form-control form-control-sm" value="{{ $sampai }}" placeholder="Sampai">
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-primary flex-fill">
                    <i class="bi bi-search"></i> Filter
                </button>
                <a href="{{ route('transaksi.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-x"></i>
                </a>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-custom mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>No. Nota</th>
                    <th>Pelanggan</th>
                    <th>Kendaraan</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksis as $t)
                <tr>
                    <td class="text-muted" style="font-size:.78rem;">{{ $transaksis->firstItem() + $loop->index }}</td>
                    <td><code style="font-size:.78rem;color:#4f46e5;">{{ $t->no_nota }}</code></td>
                    <td class="fw-semibold">{{ $t->pelanggan->nama ?? '-' }}</td>
                    <td>
                        <div style="font-size:.8rem;">{{ $t->plat_nomor ?? '-' }}</div>
                        <div style="font-size:.73rem;color:#64748b;">{{ $t->merk_kendaraan ?? '' }}</div>
                    </td>
                    <td style="font-size:.82rem;">{{ $t->tanggal->format('d/m/Y') }}</td>
                    <td class="fw-semibold" style="font-size:.83rem;">Rp {{ number_format($t->total_bayar,0,',','.') }}</td>
                    <td>
                        <span class="badge bg-light text-dark border" style="font-size:.7rem;">
                            {{ ucfirst($t->metode_pembayaran) }}
                        </span>
                    </td>
                    <td>{!! $t->status_badge !!}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('transaksi.show', $t->id_transaksi) }}"
                               class="btn btn-xs btn-outline-primary" title="Detail"
                               style="font-size:.72rem;padding:.2rem .45rem;">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('transaksi.nota', $t->id_transaksi) }}"
                               class="btn btn-xs btn-outline-success" title="Cetak Nota" target="_blank"
                               style="font-size:.72rem;padding:.2rem .45rem;">
                                <i class="bi bi-printer"></i>
                            </a>
                            <form action="{{ route('transaksi.destroy', $t->id_transaksi) }}" method="POST"
                                  onsubmit="return confirm('Hapus transaksi ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-xs btn-outline-danger" title="Hapus"
                                    style="font-size:.72rem;padding:.2rem .45rem;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                        Tidak ada data transaksi.
                        <a href="{{ route('transaksi.create') }}" class="d-block mt-2 small text-decoration-none" style="color:#4f46e5">
                            + Tambah transaksi pertama
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($transaksis->hasPages())
    <div class="p-3 d-flex justify-content-end">
        {{ $transaksis->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
