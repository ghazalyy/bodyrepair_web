@extends('layouts.app')
@section('title', 'Edit Layanan')
@section('page-title', 'Edit Layanan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('layanan.index') }}" class="text-decoration-none" style="color:#4f46e5">Layanan</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-lg-6">
    <div class="card-custom">
        <div class="card-header-custom"><i class="bi bi-pencil-square me-2"></i>Edit Layanan</div>
        <div class="p-4">
            <form action="{{ route('layanan.update', $layanan->id_layanan) }}" method="POST" novalidate>
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label" for="nama_layanan">Nama Layanan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_layanan" id="nama_layanan"
                        class="form-control @error('nama_layanan') is-invalid @enderror"
                        value="{{ old('nama_layanan', $layanan->nama_layanan) }}" required maxlength="150">
                    @error('nama_layanan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="harga">Harga (Rp) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="harga" id="harga"
                            class="form-control @error('harga') is-invalid @enderror"
                            value="{{ old('harga', $layanan->harga) }}" min="0" required>
                    </div>
                    @error('harga')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $layanan->deskripsi) }}</textarea>
                </div>
                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input type="checkbox" name="aktif" id="aktif" class="form-check-input" value="1"
                            {{ old('aktif', $layanan->aktif) ? 'checked' : '' }}>
                        <label class="form-check-label" for="aktif">Layanan Aktif</label>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('layanan.index') }}" class="btn btn-outline-secondary flex-fill">Batal</a>
                    <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-save2"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection
