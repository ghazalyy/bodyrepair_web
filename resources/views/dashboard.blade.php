@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@push('styles')
<style>
    .gradient-green  { background: linear-gradient(135deg, #10b981, #059669); }
    .gradient-red    { background: linear-gradient(135deg, #ef4444, #dc2626); }
    .gradient-blue   { background: linear-gradient(135deg, #3b82f6, #2563eb); }
    .gradient-purple { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
    .gradient-amber  { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .gradient-indigo { background: linear-gradient(135deg, #6366f1, #4f46e5); }
    .stat-icon.gradient-green,
    .stat-icon.gradient-red,
    .stat-icon.gradient-blue,
    .stat-icon.gradient-purple,
    .stat-icon.gradient-amber,
    .stat-icon.gradient-indigo { color: white; }
</style>
@endpush

@section('content')

{{-- Filter Bulan --}}
<form method="GET" class="d-flex align-items-center gap-2 mb-4 flex-wrap">
    <label class="me-1 fw-semibold" style="font-size:.83rem;">Tampilkan bulan:</label>
    <select name="bulan" class="form-select form-select-sm" style="width:auto;" onchange="this.form.submit()">
        @foreach(range(1,12) as $m)
        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
        </option>
        @endforeach
    </select>
    <select name="tahun" class="form-select form-select-sm" style="width:auto;" onchange="this.form.submit()">
        @foreach(range(now()->year - 2, now()->year + 1) as $y)
        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
        @endforeach
    </select>
</form>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-icon gradient-green"><i class="bi bi-arrow-up-circle-fill"></i></div>
            <div class="stat-value" style="font-size:.95rem;">Rp {{ number_format($totalPemasukan,0,',','.') }}</div>
            <div class="stat-label">Total Pemasukan</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-icon gradient-red"><i class="bi bi-arrow-down-circle-fill"></i></div>
            <div class="stat-value" style="font-size:.95rem;">Rp {{ number_format($totalPengeluaran,0,',','.') }}</div>
            <div class="stat-label">Total Pengeluaran</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-icon {{ $totalLaba >= 0 ? 'gradient-indigo' : 'gradient-red' }}">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div class="stat-value" style="font-size:.95rem;">Rp {{ number_format(abs($totalLaba),0,',','.') }}</div>
            <div class="stat-label">{{ $totalLaba >= 0 ? 'Laba Bersih' : 'Rugi Bersih' }}</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-icon gradient-blue"><i class="bi bi-receipt-cutoff"></i></div>
            <div class="stat-value">{{ $totalTransaksi }}</div>
            <div class="stat-label">Total Transaksi</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-icon gradient-green"><i class="bi bi-check-circle-fill"></i></div>
            <div class="stat-value">{{ $transaksiLunas }}</div>
            <div class="stat-label">Transaksi Lunas</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="stat-icon gradient-purple"><i class="bi bi-people-fill"></i></div>
            <div class="stat-value">{{ $totalPelanggan }}</div>
            <div class="stat-label">Total Pelanggan</div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Chart --}}
    <div class="col-lg-8">
        <div class="card-custom h-100">
            <div class="card-header-custom">
                <span><i class="bi bi-bar-chart-line me-2 text-indigo-500"></i>Grafik Keuangan 6 Bulan Terakhir</span>
            </div>
            <div class="p-3">
                <canvas id="chartKeuangan" height="100"></canvas>
            </div>
        </div>
    </div>

    {{-- Layanan Terlaris --}}
    <div class="col-lg-4">
        <div class="card-custom h-100">
            <div class="card-header-custom">
                <span><i class="bi bi-trophy me-2 text-warning"></i>Layanan Terlaris</span>
            </div>
            <div class="p-3">
                @forelse($layananTerlaris as $i => $item)
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="fw-bold text-center rounded" style="width:28px;height:28px;background:{{ ['#4f46e5','#7c3aed','#3b82f6','#10b981','#f59e0b'][$i] }};color:#fff;font-size:.8rem;line-height:28px;">
                        {{ $i+1 }}
                    </div>
                    <div class="flex-grow-1">
                        <div style="font-size:.82rem;font-weight:600;">{{ $item->layanan->nama_layanan ?? 'N/A' }}</div>
                        <div style="font-size:.72rem;color:#64748b;">{{ $item->total_unit }} unit | Rp {{ number_format($item->total_nilai,0,',','.') }}</div>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center" style="font-size:.83rem;">Belum ada data bulan ini.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Transaksi Terbaru --}}
    <div class="col-12">
        <div class="card-custom">
            <div class="card-header-custom">
                <span><i class="bi bi-clock-history me-2"></i>Transaksi Terbaru</span>
                @if(auth()->check() && auth()->user()->isAdmin())
                <a href="{{ route('transaksi.index') }}" class="btn btn-sm btn-outline-primary" style="font-size:.75rem;">Lihat Semua</a>
                @endif
            </div>
            <div class="table-responsive">
                <table class="table table-custom mb-0">
                    <thead>
                        <tr>
                            <th>No. Nota</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            @if(auth()->check() && auth()->user()->isAdmin())<th>Aksi</th>@endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksiTerbaru as $t)
                        <tr>
                            <td><code style="font-size:.78rem;">{{ $t->no_nota }}</code></td>
                            <td>{{ $t->pelanggan->nama ?? '-' }}</td>
                            <td>{{ $t->tanggal->format('d/m/Y') }}</td>
                            <td class="fw-semibold">Rp {{ number_format($t->total_bayar,0,',','.') }}</td>
                            <td>{!! $t->status_badge !!}</td>
                            @if(auth()->check() && auth()->user()->isAdmin())
                            <td>
                                <a href="{{ route('transaksi.show', $t->id_transaksi) }}" class="btn btn-xs btn-outline-secondary" style="font-size:.72rem;padding:.2rem .5rem;">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada transaksi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const chartData = @json($chartData);
const labels      = chartData.map(d => d.label);
const pemasukan   = chartData.map(d => d.pemasukan);
const pengeluaran = chartData.map(d => d.pengeluaran);

const ctx = document.getElementById('chartKeuangan').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels,
        datasets: [
            {
                label: 'Pemasukan',
                data: pemasukan,
                backgroundColor: 'rgba(79,70,229,.85)',
                borderRadius: 6,
            },
            {
                label: 'Pengeluaran',
                data: pengeluaran,
                backgroundColor: 'rgba(239,68,68,.7)',
                borderRadius: 6,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { font: { family: 'Inter', size: 12 } } },
            tooltip: {
                callbacks: {
                    label: ctx => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                }
            }
        },
        scales: {
            y: {
                ticks: {
                    callback: v => 'Rp ' + (v/1000000).toFixed(1) + 'jt',
                    font: { family: 'Inter', size: 11 }
                },
                grid: { color: 'rgba(0,0,0,.05)' }
            },
            x: { ticks: { font: { family: 'Inter', size: 11 } }, grid: { display: false } }
        }
    }
});
</script>
@endpush
