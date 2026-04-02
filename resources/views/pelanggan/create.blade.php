@extends('layouts.app')
@section('title', 'Tambah Pelanggan')
@section('page-title', 'Tambah Pelanggan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pelanggan.index') }}" class="text-decoration-none" style="color:#4f46e5">Pelanggan</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-lg-6">
    <div class="card-custom">
        <div class="card-header-custom"><i class="bi bi-person-plus me-2"></i>Form Tambah Pelanggan</div>
        <div class="p-4">
            <form action="{{ route('pelanggan.store') }}" method="POST" novalidate>
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="nama">Nama Pelanggan <span class="text-danger">*</span></label>
                    <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                        value="{{ old('nama') }}" maxlength="100" required placeholder="Nama lengkap pelanggan">
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="no_telepon">No. Telepon</label>
                    <input type="text" name="no_telepon" id="no_telepon" class="form-control @error('no_telepon') is-invalid @enderror"
                        value="{{ old('no_telepon') }}" maxlength="20" placeholder="08xxxxxxxxxx">
                    @error('no_telepon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label class="form-label" for="alamat">Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror"
                        rows="3" placeholder="Alamat lengkap pelanggan">{{ old('alamat') }}</textarea>
                    @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('pelanggan.index') }}" class="btn btn-outline-secondary flex-fill">Batal</a>
                    <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-save2"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection
