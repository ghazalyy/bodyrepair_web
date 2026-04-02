@extends('layouts.app')
@section('title', 'Tambah Layanan')
@section('page-title', 'Tambah Layanan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('layanan.index') }}" class="text-decoration-none" style="color:#4f46e5">Layanan</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-lg-6">
    <div class="card-custom">
        <div class="card-header-custom"><i class="bi bi-plus-circle me-2"></i>Form Tambah Layanan</div>
        <div class="p-4">
            <form action="{{ route('layanan.store') }}" method="POST" novalidate>
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="nama_layanan">Nama Layanan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_layanan" id="nama_layanan"
                        class="form-control @error('nama_layanan') is-invalid @enderror"
                        value="{{ old('nama_layanan') }}" maxlength="150" required placeholder="Contoh: Cat Ulang Body">
                    @error('nama_layanan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="harga">Harga (Rp) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="harga" id="harga"
                            class="form-control @error('harga') is-invalid @enderror"
                            value="{{ old('harga', 0) }}" min="0" required>
                    </div>
                    @error('harga')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="deskripsi">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi"
                        class="form-control" rows="3"
                        placeholder="Deskripsi singkat layanan">{{ old('deskripsi') }}</textarea>
                </div>
                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input type="checkbox" name="aktif" id="aktif" class="form-check-input" value="1"
                            {{ old('aktif', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="aktif">Layanan Aktif</label>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('layanan.index') }}" class="btn btn-outline-secondary flex-fill">Batal</a>
                    <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-save2"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection
