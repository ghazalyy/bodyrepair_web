@extends('layouts.app')

@section('title', 'Lembar Saran dan Masukan')
@section('page-title', 'Lembar Saran dan Masukan Pengembangan')
@section('breadcrumb')
    <li class="breadcrumb-item active">Lembar Saran</li>
@endsection

@push('styles')
<style>
    .feedback-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    .feedback-header {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: #fff;
        padding: 2rem 2rem 1.5rem;
        text-align: center;
    }
    .feedback-header .form-title {
        font-size: 1.35rem;
        font-weight: 800;
        letter-spacing: .02em;
        margin-bottom: .25rem;
    }
    .feedback-header .form-subtitle {
        font-size: .85rem;
        opacity: .85;
    }
    .feedback-body {
        padding: 1.75rem 2rem;
    }

    .section-label {
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #4f46e5;
        margin-bottom: 1.25rem;
        padding-bottom: .4rem;
        border-bottom: 2px solid #e0e7ff;
        display: flex;
        align-items: center;
        gap: .5rem;
    }
    .section-label i { font-size: .9rem; }

    .section-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.25rem;
    }
    .section-card .section-title {
        font-size: .88rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: .75rem;
        display: flex;
        align-items: flex-start;
        gap: .6rem;
    }
    .section-card .section-title .section-num {
        background: #4f46e5;
        color: #fff;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        font-size: .72rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-top: .05rem;
    }
    .section-card textarea {
        background: #fff;
        border-color: #d1d5db;
        resize: vertical;
        min-height: 100px;
    }
    .section-card textarea:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79,70,229,.1);
    }

    .identity-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1rem;
    }
    @media (max-width: 768px) {
        .identity-grid { grid-template-columns: 1fr; }
        .feedback-body { padding: 1.25rem 1rem; }
        .feedback-header { padding: 1.5rem 1rem 1.25rem; }
        .feedback-header .form-title { font-size: 1.1rem; }
    }
    @media (min-width: 769px) and (max-width: 992px) {
        .identity-grid { grid-template-columns: 1fr 1fr; }
    }

    .btn-submit {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        border: none;
        color: #fff;
        padding: .65rem 2rem;
        font-size: .9rem;
        font-weight: 600;
        border-radius: 8px;
        transition: opacity .2s, transform .1s;
    }
    .btn-submit:hover { opacity: .9; transform: translateY(-1px); color: #fff; }
    .btn-submit:active { transform: translateY(0); }
    .btn-submit i { margin-right: .4rem; }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-10 col-xxl-9">

        <div class="feedback-card shadow-sm">

            {{-- Decorative Header --}}
            <div class="feedback-header">
                <div class="form-title">
                    <i class="bi bi-file-earmark-text me-2"></i>
                    Lembar Saran dan Masukan Pengembangan
                </div>
                <div class="form-subtitle">
                    Feedback &amp; Development Suggestions Sheet
                </div>
            </div>

            <div class="feedback-body">

                {{-- Validation Errors --}}
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Harap periksa kembali isian Anda:</strong>
                    <ul class="mb-0 mt-1 ps-3">
                        @foreach($errors->all() as $error)
                            <li style="font-size:.83rem;">{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <form action="{{ route('feedback.store') }}" method="POST" novalidate>
                    @csrf

                    {{-- ── Identity Fields ── --}}
                    <div class="section-label">
                        <i class="bi bi-person-fill"></i> Identitas Pengisi
                    </div>

                    <div class="identity-grid mb-4">
                        <div>
                            <label for="nama" class="form-label">
                                Nama <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                id="nama"
                                name="nama"
                                class="form-control @error('nama') is-invalid @enderror"
                                placeholder="Nama lengkap"
                                value="{{ old('nama') }}"
                                required
                            >
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="jabatan" class="form-label">
                                Jabatan / Peran <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                id="jabatan"
                                name="jabatan"
                                class="form-control @error('jabatan') is-invalid @enderror"
                                placeholder="Jabatan atau peran Anda"
                                value="{{ old('jabatan') }}"
                                required
                            >
                            @error('jabatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal" class="form-label">
                                Tanggal <span class="text-danger">*</span>
                            </label>
                            <input
                                type="date"
                                id="tanggal"
                                name="tanggal"
                                class="form-control @error('tanggal') is-invalid @enderror"
                                value="{{ old('tanggal', now()->format('Y-m-d')) }}"
                                required
                            >
                            @error('tanggal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- ── Feedback Sections ── --}}
                    <div class="section-label">
                        <i class="bi bi-chat-square-text-fill"></i> Isian Saran dan Masukan
                    </div>

                    {{-- Section 1 --}}
                    <div class="section-card">
                        <div class="section-title">
                            <span class="section-num">1</span>
                            <span>Kesan Umum tentang Demo</span>
                        </div>
                        <textarea
                            name="kesan_umum"
                            class="form-control @error('kesan_umum') is-invalid @enderror"
                            rows="4"
                            placeholder="Tuliskan kesan umum Anda setelah melihat demo aplikasi..."
                            required
                        >{{ old('kesan_umum') }}</textarea>
                        @error('kesan_umum')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Section 2 --}}
                    <div class="section-card">
                        <div class="section-title">
                            <span class="section-num">2</span>
                            <span>Fitur atau Fungsi yang Sudah Baik</span>
                        </div>
                        <textarea
                            name="fitur_baik"
                            class="form-control @error('fitur_baik') is-invalid @enderror"
                            rows="4"
                            placeholder="Sebutkan fitur atau fungsi yang menurut Anda sudah berjalan dengan baik..."
                            required
                        >{{ old('fitur_baik') }}</textarea>
                        @error('fitur_baik')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Section 3 --}}
                    <div class="section-card">
                        <div class="section-title">
                            <span class="section-num">3</span>
                            <span>Masalah atau Kekurangan yang Ditemukan</span>
                        </div>
                        <textarea
                            name="masalah"
                            class="form-control @error('masalah') is-invalid @enderror"
                            rows="4"
                            placeholder="Tuliskan masalah, kekurangan, atau hal yang perlu diperbaiki..."
                            required
                        >{{ old('masalah') }}</textarea>
                        @error('masalah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Section 4 --}}
                    <div class="section-card">
                        <div class="section-title">
                            <span class="section-num">4</span>
                            <span>Saran Perbaikan atau Pengembangan</span>
                        </div>
                        <textarea
                            name="saran"
                            class="form-control @error('saran') is-invalid @enderror"
                            rows="4"
                            placeholder="Berikan saran konkret untuk perbaikan atau pengembangan aplikasi ke depannya..."
                            required
                        >{{ old('saran') }}</textarea>
                        @error('saran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-submit">
                            <i class="bi bi-send-fill"></i> Kirim Masukan
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>
@endsection
