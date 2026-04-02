@extends('layouts.app')
@section('title', 'Input Transaksi')
@section('page-title', 'Input Transaksi')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('transaksi.index') }}" class="text-decoration-none" style="color:#4f46e5">Transaksi</a></li>
    <li class="breadcrumb-item active">Tambah</li>
@endsection

@push('styles')
<style>
    .layanan-row { background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1rem; margin-bottom:.75rem; transition:box-shadow .2s; }
    .layanan-row:hover { box-shadow: 0 4px 12px rgba(0,0,0,.06); }
    .btn-remove-row { color:#ef4444;border:none;background:none;cursor:pointer;font-size:1.1rem; padding:.25rem .5rem; }
    .btn-remove-row:hover { color:#dc2626; }
    #total-display { font-size:1.5rem; font-weight:800; color:#4f46e5; }
    #sisa-display { font-size:1rem; font-weight:700; color:#ef4444; }
</style>
@endpush

@section('content')
<form action="{{ route('transaksi.store') }}" method="POST" id="form-transaksi" novalidate>
@csrf
<div class="row g-3">

    {{-- Kolom Kiri: Info Transaksi --}}
    <div class="col-lg-5">
        <div class="card-custom mb-3">
            <div class="card-header-custom"><i class="bi bi-person-badge me-2"></i>Data Pelanggan & Kendaraan</div>
            <div class="p-4">
                <div class="mb-3">
                    <label class="form-label" for="id_pelanggan">Pelanggan <span class="text-danger">*</span></label>
                    <select name="id_pelanggan" id="id_pelanggan"
                        class="form-select @error('id_pelanggan') is-invalid @enderror" required>
                        <option value="">-- Pilih Pelanggan --</option>
                        @foreach($pelanggans as $p)
                        <option value="{{ $p->id_pelanggan }}" {{ old('id_pelanggan') == $p->id_pelanggan ? 'selected':'' }}>
                            {{ $p->nama }} {{ $p->no_telepon ? '(' . $p->no_telepon . ')' : '' }}
                        </option>
                        @endforeach
                    </select>
                    @error('id_pelanggan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="tanggal">Tanggal Transaksi <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" id="tanggal"
                        class="form-control @error('tanggal') is-invalid @enderror"
                        value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row g-2">
                    <div class="col">
                        <label class="form-label" for="plat_nomor">Plat Nomor</label>
                        <input type="text" name="plat_nomor" id="plat_nomor"
                            class="form-control text-uppercase" placeholder="B 1234 ABC"
                            value="{{ old('plat_nomor') }}" maxlength="20">
                    </div>
                    <div class="col">
                        <label class="form-label" for="merk_kendaraan">Merk Kendaraan</label>
                        <input type="text" name="merk_kendaraan" id="merk_kendaraan"
                            class="form-control" placeholder="Toyota Avanza"
                            value="{{ old('merk_kendaraan') }}" maxlength="100">
                    </div>
                </div>

                <div class="mb-3 mt-3">
                    <label class="form-label" for="catatan">Catatan</label>
                    <textarea name="catatan" id="catatan" class="form-control" rows="2"
                        placeholder="Catatan tambahan (opsional)">{{ old('catatan') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Pembayaran --}}
        <div class="card-custom">
            <div class="card-header-custom"><i class="bi bi-wallet2 me-2"></i>Pembayaran</div>
            <div class="p-4">
                <div class="mb-3">
                    <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                    <div class="d-flex gap-2 flex-wrap">
                        @foreach(['tunai'=>'Tunai','transfer'=>'Transfer','qris'=>'QRIS'] as $val=>$label)
                        <div class="form-check form-check-inline">
                            <input type="radio" name="metode_pembayaran" id="mp_{{ $val }}" value="{{ $val }}"
                                class="form-check-input" {{ old('metode_pembayaran','tunai') === $val ? 'checked' : '' }}>
                            <label class="form-check-label" for="mp_{{ $val }}" style="font-size:.85rem;">{{ $label }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status Transaksi <span class="text-danger">*</span></label>
                    <select name="status_transaksi" id="status_transaksi" class="form-select" onchange="toggleDp(this.value)">
                        <option value="Lunas"   {{ old('status_transaksi','Lunas') === 'Lunas'   ? 'selected':'' }}>Lunas</option>
                        <option value="DP"      {{ old('status_transaksi') === 'DP'      ? 'selected':'' }}>DP (Bayar Sebagian)</option>
                        <option value="Pending" {{ old('status_transaksi') === 'Pending' ? 'selected':'' }}>Pending</option>
                    </select>
                </div>

                <div id="dp-section" class="mb-3" style="display:none;">
                    <label class="form-label" for="dp">Jumlah DP (Bayar di Muka)</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="dp" id="dp" class="form-control"
                            placeholder="0" min="0" value="{{ old('dp', 0) }}" oninput="hitungSisa()">
                    </div>
                    <div class="mt-2 p-2 rounded" style="background:#fef2f2;">
                        <small>Sisa bayar: <strong id="sisa-display">Rp 0</strong></small>
                    </div>
                </div>

                {{-- Ringkasan Total --}}
                <div class="p-3 rounded-3 text-center" style="background:linear-gradient(135deg,#4f46e5,#7c3aed)">
                    <div style="color:rgba(255,255,255,.7);font-size:.78rem;">TOTAL PEMBAYARAN</div>
                    <div id="total-display" style="color:#fff;">Rp 0</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Layanan --}}
    <div class="col-lg-7">
        <div class="card-custom">
            <div class="card-header-custom">
                <span><i class="bi bi-tools me-2"></i>Layanan yang Dikerjakan <span class="text-danger">*</span></span>
                <button type="button" class="btn btn-sm btn-primary" id="btn-tambah-layanan" style="font-size:.78rem;">
                    <i class="bi bi-plus-lg"></i> Tambah Layanan
                </button>
            </div>
            <div class="p-4">
                @error('layanan')
                <div class="alert alert-danger py-2" style="font-size:.82rem;">{{ $message }}</div>
                @enderror

                <div id="layanan-container">
                    {{-- Row layanan pertama (default) --}}
                    <div class="layanan-row" id="row-0">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong style="font-size:.82rem;">Layanan #1</strong>
                            <button type="button" class="btn-remove-row" onclick="removeRow(this)" disabled>
                                <i class="bi bi-x-circle"></i>
                            </button>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Nama Layanan</label>
                            <select name="layanan[0][id]" class="form-select layanan-select" required onchange="updateHarga(this, 0)">
                                <option value="">-- Pilih Layanan --</option>
                                @foreach($layanans as $l)
                                <option value="{{ $l->id_layanan }}" data-harga="{{ $l->harga }}">
                                    {{ $l->nama_layanan }} — Rp {{ number_format($l->harga,0,',','.') }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row g-2">
                            <div class="col-4">
                                <label class="form-label">Jumlah</label>
                                <input type="number" name="layanan[0][jumlah]" class="form-control jumlah-input"
                                    value="1" min="1" oninput="hitungTotal()">
                            </div>
                            <div class="col-4">
                                <label class="form-label">Harga Satuan</label>
                                <input type="text" class="form-control harga-satuan bg-light" readonly placeholder="Rp 0">
                            </div>
                            <div class="col-4">
                                <label class="form-label">Subtotal</label>
                                <input type="text" class="form-control subtotal bg-light" readonly placeholder="Rp 0">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-3 pt-3 border-top">
                    <table class="text-end" style="font-size:.85rem;">
                        <tr>
                            <td class="pe-3 text-muted">Jumlah Layanan:</td>
                            <td class="fw-semibold"><span id="jml-layanan">0</span> item</td>
                        </tr>
                        <tr>
                            <td class="pe-3 text-muted">Total:</td>
                            <td class="fw-bold fs-5" style="color:#4f46e5;"><span id="total-kanan">Rp 0</span></td>
                        </tr>
                    </table>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary flex-fill">
                        <i class="bi bi-arrow-left"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary flex-fill" id="btn-simpan">
                        <i class="bi bi-save2"></i> Simpan Transaksi
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
</form>
@endsection

@push('scripts')
<script>
let rowCount = 1;
const layanans = @json($layanans->map(fn($l) => ['id' => $l->id_layanan, 'nama' => $l->nama_layanan, 'harga' => $l->harga]));

// Select2 pelanggan
$('#id_pelanggan').select2({ theme: 'bootstrap-5', placeholder: '-- Pilih Pelanggan --' });

// Toggle DP section
function toggleDp(val) {
    document.getElementById('dp-section').style.display = val === 'DP' ? 'block' : 'none';
    hitungTotal();
}
toggleDp(document.getElementById('status_transaksi').value);

function buildOptions(selectedId = '') {
    let opts = '<option value="">-- Pilih Layanan --</option>';
    layanans.forEach(l => {
        opts += `<option value="${l.id}" data-harga="${l.harga}" ${l.id == selectedId ? 'selected' : ''}>
            ${l.nama} — Rp ${Number(l.harga).toLocaleString('id-ID')}
        </option>`;
    });
    return opts;
}

// Tambah baris layanan
document.getElementById('btn-tambah-layanan').addEventListener('click', () => {
    const idx = rowCount++;
    const div = document.createElement('div');
    div.className = 'layanan-row';
    div.id = `row-${idx}`;
    div.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-2">
            <strong style="font-size:.82rem;">Layanan #${idx+1}</strong>
            <button type="button" class="btn-remove-row" onclick="removeRow(this)">
                <i class="bi bi-x-circle"></i>
            </button>
        </div>
        <div class="mb-2">
            <label class="form-label">Nama Layanan</label>
            <select name="layanan[${idx}][id]" class="form-select layanan-select" required onchange="updateHarga(this, ${idx})">
                ${buildOptions()}
            </select>
        </div>
        <div class="row g-2">
            <div class="col-4">
                <label class="form-label">Jumlah</label>
                <input type="number" name="layanan[${idx}][jumlah]" class="form-control jumlah-input" value="1" min="1" oninput="hitungTotal()">
            </div>
            <div class="col-4">
                <label class="form-label">Harga Satuan</label>
                <input type="text" class="form-control harga-satuan bg-light" readonly placeholder="Rp 0">
            </div>
            <div class="col-4">
                <label class="form-label">Subtotal</label>
                <input type="text" class="form-control subtotal bg-light" readonly placeholder="Rp 0">
            </div>
        </div>`;
    document.getElementById('layanan-container').appendChild(div);
    // Update row numbers
    updateRowNumbers();
});

function removeRow(btn) {
    btn.closest('.layanan-row').remove();
    updateRowNumbers();
    hitungTotal();
}

function updateRowNumbers() {
    document.querySelectorAll('.layanan-row strong').forEach((el, i) => {
        el.textContent = `Layanan #${i+1}`;
    });
    // Enable/disable all remove buttons
    const rows = document.querySelectorAll('.layanan-row');
    rows.forEach(r => {
        r.querySelector('.btn-remove-row').disabled = rows.length === 1;
    });
}

function updateHarga(select, idx) {
    const opt   = select.options[select.selectedIndex];
    const harga = parseFloat(opt.dataset.harga) || 0;
    const row   = select.closest('.layanan-row');
    const jml   = parseInt(row.querySelector('.jumlah-input').value) || 1;
    const subtot = harga * jml;

    row.querySelector('.harga-satuan').value = 'Rp ' + harga.toLocaleString('id-ID');
    row.querySelector('.subtotal').value      = 'Rp ' + subtot.toLocaleString('id-ID');
    hitungTotal();
}

function hitungTotal() {
    let total = 0, jmlItem = 0;
    document.querySelectorAll('.layanan-row').forEach(row => {
        const sel   = row.querySelector('.layanan-select');
        const opt   = sel.options[sel.selectedIndex];
        const harga = parseFloat(opt?.dataset?.harga) || 0;
        const jml   = parseInt(row.querySelector('.jumlah-input').value) || 1;
        const subtot = harga * jml;
        row.querySelector('.subtotal').value      = subtot ? 'Rp ' + subtot.toLocaleString('id-ID') : 'Rp 0';
        row.querySelector('.harga-satuan').value  = harga  ? 'Rp ' + harga.toLocaleString('id-ID')  : 'Rp 0';
        if (harga > 0) { total += subtot; jmlItem++; }
    });

    document.getElementById('total-display').textContent = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('total-kanan').textContent   = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('jml-layanan').textContent   = jmlItem;
    hitungSisa();
}

function hitungSisa() {
    const totalEl = document.getElementById('total-display').textContent.replace(/[^0-9]/g,'');
    const total   = parseInt(totalEl) || 0;
    const dp      = parseInt(document.getElementById('dp')?.value) || 0;
    const sisa    = Math.max(0, total - dp);
    const sisaEl  = document.getElementById('sisa-display');
    if (sisaEl) sisaEl.textContent = 'Rp ' + sisa.toLocaleString('id-ID');
}
</script>
@endpush
