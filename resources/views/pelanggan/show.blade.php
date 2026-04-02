@extends('layouts.app')
@section('title', 'Detail Pelanggan')
@section('page-title', 'Detail Pelanggan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pelanggan.index') }}" class="text-decoration-none" style="color:#4f46e5">Pelanggan</a></li>
    <li class="breadcrumb-item active">{{ $pelanggan->nama }}</li>
@endsection

@section('content')
<div class="row g-3">
    <div class="col-md-4">
        <div class="card-custom">
            <div class="card-header-custom"><i class="bi bi-person-circle me-2"></i>Profil Pelanggan</div>
            <div class="p-4 text-center">
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle"
                     style="width:80px;height:80px;background:linear-gradient(135deg,#4f46e5,#7c3aed);font-size:2rem;color:#fff;font-weight:700;">
                    {{ strtoupper(substr($pelanggan->nama, 0, 1)) }}
                </div>
                <h5 class="fw-bold mb-1">{{ $pelanggan->nama }}</h5>
                <p class="text-muted mb-3" style="font-size:.83rem;">{{ $pelanggan->no_telepon ?? 'No. Telepon tidak tersedia' }}</p>
                <p style="font-size:.82rem;color:#475569;">{{ $pelanggan->alamat ?? 'Alamat tidak tersedia' }}</p>

                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('pelanggan.edit', $pelanggan->id_pelanggan) }}" class="btn btn-sm btn-outline-primary flex-fill">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card-custom">
            <div class="card-header-custom"><i class="bi bi-clock-history me-2"></i>Riwayat Transaksi</div>
            <div class="table-responsive">
                <table class="table table-custom mb-0">
                    <thead>
                        <tr><th>No. Nota</th><th>Tanggal</th><th>Total</th><th>Status</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @forelse($pelanggan->transaksis as $t)
                        <tr>
                            <td><code style="font-size:.78rem;color:#4f46e5;">{{ $t->no_nota }}</code></td>
                            <td style="font-size:.8rem;">{{ $t->tanggal->format('d/m/Y') }}</td>
                            <td class="fw-semibold">Rp {{ number_format($t->total_bayar,0,',','.') }}</td>
                            <td>{!! $t->status_badge !!}</td>
                            <td><a href="{{ route('transaksi.show', $t->id_transaksi) }}" class="btn btn-xs btn-outline-primary" style="font-size:.72rem;padding:.2rem .45rem;"><i class="bi bi-eye"></i></a></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-3 text-muted">Belum ada transaksi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
