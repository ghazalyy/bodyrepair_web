@extends('layouts.app')
@section('title', 'Edit Pelanggan')
@section('page-title', 'Edit Pelanggan')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pelanggan.index') }}" class="text-decoration-none" style="color:#4f46e5">Pelanggan</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
<div class="col-lg-6">
    <div class="card-custom">
        <div class="card-header-custom"><i class="bi bi-pencil-square me-2"></i>Edit Data Pelanggan</div>
        <div class="p-4">
            <form action="{{ route('pelanggan.update', $pelanggan->id_pelanggan) }}" method="POST" novalidate>
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label" for="nama">Nama Pelanggan <span class="text-danger">*</span></label>
                    <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                        value="{{ old('nama', $pelanggan->nama) }}" maxlength="100" required>
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="no_telepon">No. Telepon</label>
                    <input type="text" name="no_telepon" id="no_telepon" class="form-control @error('no_telepon') is-invalid @enderror"
                        value="{{ old('no_telepon', $pelanggan->no_telepon) }}" maxlength="20">
                    @error('no_telepon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-4">
                    <label class="form-label" for="alamat">Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control" rows="3">{{ old('alamat', $pelanggan->alamat) }}</textarea>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('pelanggan.index') }}" class="btn btn-outline-secondary flex-fill">Batal</a>
                    <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-save2"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection
