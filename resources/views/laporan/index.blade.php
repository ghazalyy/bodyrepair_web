@extends('layouts.app')
@section('title', 'Laporan Keuangan')
@section('page-title', 'Laporan Keuangan')
@section('breadcrumb')
    <li class="breadcrumb-item active">Laporan Keuangan</li>
@endsection

@section('content')

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card" style="border-left:4px solid #10b981;">
            <div class="stat-icon" style="background:#ecfdf5;color:#10b981;"><i class="bi bi-arrow-up-circle-fill"></i></div>
            <div class="stat-value" style="color:#10b981;">Rp {{ number_format($totalPemasukan,0,',','.') }}</div>
            <div class="stat-label">Total Pemasukan</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="border-left:4px solid #ef4444;">
            <div class="stat-icon" style="background:#fef2f2;color:#ef4444;"><i class="bi bi-arrow-down-circle-fill"></i></div>
            <div class="stat-value" style="color:#ef4444;">Rp {{ number_format($totalPengeluaran,0,',','.') }}</div>
            <div class="stat-label">Total Pengeluaran</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="border-left:4px solid {{ $totalLaba >= 0 ? '#4f46e5' : '#ef4444' }};">
            <div class="stat-icon" style="background:#eff6ff;color:{{ $totalLaba >= 0 ? '#4f46e5' : '#ef4444' }};"><i class="bi bi-cash-coin"></i></div>
            <div class="stat-value" style="color:{{ $totalLaba >= 0 ? '#4f46e5' : '#ef4444' }};">
                {{ $totalLaba < 0 ? '- ' : '' }}Rp {{ number_format(abs($totalLaba),0,',','.') }}
            </div>
            <div class="stat-label">{{ $totalLaba >= 0 ? 'Laba Bersih' : 'Rugi Bersih' }}</div>
        </div>
    </div>
</div>

<div class="row g-3">

    {{-- Filter & Tabel --}}
    <div class="col-lg-8">
        <div class="card-custom">
            <div class="card-header-custom">
                <span><i class="bi bi-table me-2"></i>Data Keuangan</span>
                <div class="d-flex gap-2">
                    @if(auth()->user()->isAdmin())
                    <button class="btn btn-sm btn-success" style="font-size:.76rem;" onclick="document.getElementById('modal-tambah').style.display='flex'">
                        <i class="bi bi-plus-lg"></i> Tambah Manual
                    </button>
                    @endif
                    <a href="{{ route('laporan.pdf', request()->query()) }}" class="btn btn-sm btn-outline-danger" style="font-size:.76rem;" target="_blank">
                        <i class="bi bi-filetype-pdf"></i> Export PDF
                    </a>
                </div>
            </div>

            {{-- Filter --}}
            <div class="p-3 border-bottom bg-light">
                <form method="GET" class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <input type="date" name="dari" class="form-control form-control-sm" value="{{ $dari }}">
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="sampai" class="form-control form-control-sm" value="{{ $sampai }}">
                    </div>
                    <div class="col-md-3">
                        <select name="jenis" class="form-select form-select-sm">
                            <option value="">Semua Jenis</option>
                            <option value="pemasukan"   {{ $jenis === 'pemasukan'   ? 'selected':'' }}>Pemasukan</option>
                            <option value="pengeluaran" {{ $jenis === 'pengeluaran' ? 'selected':'' }}>Pengeluaran</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-1">
                        <button type="submit" class="btn btn-sm btn-primary flex-fill"><i class="bi bi-search"></i> Filter</button>
                        <a href="{{ route('laporan.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x"></i></a>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-custom mb-0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            @if(auth()->user()->isAdmin())<th>Aksi</th>@endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dataKeuangans as $d)
                        <tr>
                            <td style="font-size:.8rem;">{{ $d->tanggal->format('d/m/Y') }}</td>
                            <td style="font-size:.8rem;">
                                {{ $d->keterangan }}
                                @if($d->id_transaksi)
                                <span class="badge bg-light text-secondary border" style="font-size:.65rem;">Dari Transaksi</span>
                                @endif
                            </td>
                            <td>
                                @if($d->jenis === 'pemasukan')
                                    <span class="badge bg-success">Pemasukan</span>
                                @else
                                    <span class="badge bg-danger">Pengeluaran</span>
                                @endif
                            </td>
                            <td class="fw-semibold {{ $d->jenis === 'pemasukan' ? 'text-success' : 'text-danger' }}" style="font-size:.82rem;">
                                {{ $d->jenis === 'pemasukan' ? '+' : '-' }} Rp {{ number_format($d->jumlah,0,',','.') }}
                            </td>
                            @if(auth()->user()->isAdmin())
                            <td>
                                @if(!$d->id_transaksi)
                                <form action="{{ route('laporan.destroy', $d->id_keuangan) }}" method="POST"
                                      onsubmit="return confirm('Hapus data ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-xs btn-outline-danger" style="font-size:.72rem;padding:.2rem .45rem;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @else
                                <span class="text-muted" style="font-size:.72rem;">-</span>
                                @endif
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Tidak ada data.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($dataKeuangans->hasPages())
            <div class="p-3 d-flex justify-content-end">{{ $dataKeuangans->links('pagination::bootstrap-5') }}</div>
            @endif
        </div>
    </div>

    {{-- Chart Harian --}}
    <div class="col-lg-4">
        <div class="card-custom">
            <div class="card-header-custom"><i class="bi bi-graph-up me-2"></i>Trend Harian</div>
            <div class="p-3" style="height:320px;">
                <canvas id="chartHarian"></canvas>
            </div>
        </div>

        @if(auth()->user()->isAdmin())
        {{-- Form Tambah Manual --}}
        <div class="card-custom mt-3">
            <div class="card-header-custom"><i class="bi bi-pencil-square me-2"></i>Input Pengeluaran Manual</div>
            <div class="p-4">
                <form action="{{ route('laporan.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="mb-2">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control form-control-sm @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Jenis</label>
                        <select name="jenis" class="form-select form-select-sm @error('jenis') is-invalid @enderror" required>
                            <option value="pengeluaran" {{ old('jenis','pengeluaran')==='pengeluaran'?'selected':'' }}>Pengeluaran</option>
                            <option value="pemasukan"   {{ old('jenis')==='pemasukan'?'selected':'' }}>Pemasukan</option>
                        </select>
                        @error('jenis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Jumlah (Rp)</label>
                        <input type="number" name="jumlah" class="form-control form-control-sm @error('jumlah') is-invalid @enderror" placeholder="0" min="1" value="{{ old('jumlah') }}" required>
                        @error('jumlah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control form-control-sm @error('keterangan') is-invalid @enderror" placeholder="Beli cat, listrik, dll." value="{{ old('keterangan') }}" required maxlength="255">
                        @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary w-100">
                        <i class="bi bi-save2"></i> Simpan
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
const harianData = @json($harianData);
new Chart(document.getElementById('chartHarian'), {
    type: 'line',
    data: {
        labels: harianData.map(d => d.tgl),
        datasets: [
            {
                label: 'Pemasukan',
                data: harianData.map(d => d.pemasukan),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16,185,129,.1)',
                tension: .3, fill: true, pointRadius: 3
            },
            {
                label: 'Pengeluaran',
                data: harianData.map(d => d.pengeluaran),
                borderColor: '#ef4444',
                backgroundColor: 'rgba(239,68,68,.08)',
                tension: .3, fill: true, pointRadius: 3
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom', labels: { font: { family: 'Inter', size: 11 } } },
            tooltip: { callbacks: { label: c => 'Rp ' + c.parsed.y.toLocaleString('id-ID') } }
        },
        scales: {
            y: {
                ticks: { callback: v => 'Rp ' + (v/1000).toFixed(0) + 'k', font: { size: 10 } },
                grid: { color: 'rgba(0,0,0,.04)' }
            },
            x: { ticks: { font: { size: 9 } }, grid: { display: false } }
        }
    }
});
</script>
@endpush
