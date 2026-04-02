@extends('layouts.app')
@section('title', 'Data Pelanggan')
@section('page-title', 'Data Pelanggan')
@section('breadcrumb')
    <li class="breadcrumb-item active">Pelanggan</li>
@endsection

@section('content')
<div class="card-custom">
    <div class="card-header-custom">
        <span><i class="bi bi-people me-2"></i>Daftar Pelanggan</span>
        <a href="{{ route('pelanggan.create') }}" class="btn btn-sm btn-primary" style="font-size:.78rem;">
            <i class="bi bi-plus-lg"></i> Tambah Pelanggan
        </a>
    </div>

    <div class="p-3 border-bottom bg-light">
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control form-control-sm"
                placeholder="Cari nama, telepon, alamat..." value="{{ $keyword }}">
            <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
            <a href="{{ route('pelanggan.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x"></i></a>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-custom mb-0">
            <thead>
                <tr><th>#</th><th>Nama</th><th>No. Telepon</th><th>Alamat</th><th>Transaksi</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($pelanggans as $p)
                <tr>
                    <td class="text-muted" style="font-size:.78rem;">{{ $pelanggans->firstItem() + $loop->index }}</td>
                    <td class="fw-semibold">{{ $p->nama }}</td>
                    <td>{{ $p->no_telepon ?? '-' }}</td>
                    <td style="font-size:.8rem;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $p->alamat ?? '-' }}</td>
                    <td>
                        <span class="badge bg-info text-dark" style="font-size:.7rem;">
                            {{ $p->transaksis_count ?? $p->transaksis()->count() }} transaksi
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('pelanggan.show', $p->id_pelanggan) }}" class="btn btn-xs btn-outline-primary" title="Detail" style="font-size:.72rem;padding:.2rem .45rem;"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('pelanggan.edit', $p->id_pelanggan) }}" class="btn btn-xs btn-outline-warning" title="Edit" style="font-size:.72rem;padding:.2rem .45rem;"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('pelanggan.destroy', $p->id_pelanggan) }}" method="POST" onsubmit="return confirm('Hapus pelanggan ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-xs btn-outline-danger" title="Hapus" style="font-size:.72rem;padding:.2rem .45rem;"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted"><i class="bi bi-inbox fs-2 d-block mb-2"></i>Tidak ada pelanggan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pelanggans->hasPages())
    <div class="p-3 d-flex justify-content-end">{{ $pelanggans->links('pagination::bootstrap-5') }}</div>
    @endif
</div>
@endsection
