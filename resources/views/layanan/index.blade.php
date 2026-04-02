@extends('layouts.app')
@section('title', 'Data Layanan')
@section('page-title', 'Data Layanan')
@section('breadcrumb')
    <li class="breadcrumb-item active">Layanan</li>
@endsection

@section('content')
<div class="card-custom">
    <div class="card-header-custom">
        <span><i class="bi bi-tools me-2"></i>Daftar Layanan</span>
        <a href="{{ route('layanan.create') }}" class="btn btn-sm btn-primary" style="font-size:.78rem;">
            <i class="bi bi-plus-lg"></i> Tambah Layanan
        </a>
    </div>

    <div class="p-3 border-bottom bg-light">
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control form-control-sm"
                placeholder="Cari nama layanan..." value="{{ $keyword }}">
            <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
            <a href="{{ route('layanan.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x"></i></a>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-custom mb-0">
            <thead>
                <tr><th>#</th><th>Nama Layanan</th><th>Harga</th><th>Deskripsi</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($layanans as $l)
                <tr>
                    <td class="text-muted" style="font-size:.78rem;">{{ $layanans->firstItem() + $loop->index }}</td>
                    <td class="fw-semibold">{{ $l->nama_layanan }}</td>
                    <td class="fw-bold" style="color:#4f46e5;">Rp {{ number_format($l->harga,0,',','.') }}</td>
                    <td style="font-size:.8rem;color:#64748b;max-width:200px;">{{ Str::limit($l->deskripsi, 60, '...') ?? '-' }}</td>
                    <td>
                        @if($l->aktif)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('layanan.edit', $l->id_layanan) }}" class="btn btn-xs btn-outline-warning" style="font-size:.72rem;padding:.2rem .45rem;"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('layanan.destroy', $l->id_layanan) }}" method="POST" onsubmit="return confirm('Hapus layanan ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-xs btn-outline-danger" style="font-size:.72rem;padding:.2rem .45rem;"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted"><i class="bi bi-inbox fs-2 d-block mb-2"></i>Belum ada layanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($layanans->hasPages())
    <div class="p-3 d-flex justify-content-end">{{ $layanans->links('pagination::bootstrap-5') }}</div>
    @endif
</div>
@endsection
