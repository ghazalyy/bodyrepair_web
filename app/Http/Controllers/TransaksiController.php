<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Pelanggan;
use App\Models\Layanan;
use App\Models\DataKeuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('search', '');
        $status  = $request->get('status', '');
        $dari    = $request->get('dari', '');
        $sampai  = $request->get('sampai', '');

        $transaksis = Transaksi::with('pelanggan')
            ->when($keyword, fn($q) =>
                $q->where('no_nota', 'like', "%$keyword%")
                  ->orWhereHas('pelanggan', fn($p) => $p->where('nama', 'like', "%$keyword%"))
                  ->orWhere('plat_nomor', 'like', "%$keyword%")
            )
            ->when($status, fn($q) => $q->where('status_transaksi', $status))
            ->when($dari,   fn($q) => $q->whereDate('tanggal', '>=', $dari))
            ->when($sampai, fn($q) => $q->whereDate('tanggal', '<=', $sampai))
            ->latest('tanggal')->paginate(15)->withQueryString();

        return view('transaksi.index', compact('transaksis', 'keyword', 'status', 'dari', 'sampai'));
    }

    public function create()
    {
        $pelanggans = Pelanggan::orderBy('nama')->get();
        $layanans   = Layanan::aktif()->orderBy('nama_layanan')->get();
        return view('transaksi.create', compact('pelanggans', 'layanans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pelanggan'      => ['required', 'exists:pelanggans,id_pelanggan'],
            'tanggal'           => ['required', 'date'],
            'plat_nomor'        => ['nullable', 'string', 'max:20'],
            'merk_kendaraan'    => ['nullable', 'string', 'max:100'],
            'metode_pembayaran' => ['required', 'in:tunai,transfer,qris'],
            'status_transaksi'  => ['required', 'in:DP,Lunas,Pending'],
            'dp'                => ['nullable', 'numeric', 'min:0'],
            'catatan'           => ['nullable', 'string', 'max:500'],
            'layanan'           => ['required', 'array', 'min:1'],
            'layanan.*.id'      => ['required', 'exists:layanans,id_layanan'],
            'layanan.*.jumlah'  => ['required', 'integer', 'min:1'],
        ], [
            'id_pelanggan.required' => 'Pelanggan wajib dipilih.',
            'tanggal.required'      => 'Tanggal transaksi wajib diisi.',
            'layanan.required'      => 'Minimal satu layanan harus dipilih.',
            'layanan.min'           => 'Minimal satu layanan harus dipilih.',
        ]);

        try {
            DB::transaction(function () use ($validated, $request) {
                // Hitung total dari layanan yang dipilih
                $totalBayar = 0;
                $detailItems = [];

                foreach ($validated['layanan'] as $item) {
                    $layanan      = Layanan::find($item['id']);
                    $hargaSatuan  = $layanan->harga;
                    $subtotal     = $hargaSatuan * $item['jumlah'];
                    $totalBayar  += $subtotal;

                    $detailItems[] = [
                        'id_layanan'   => $layanan->id_layanan,
                        'jumlah'       => $item['jumlah'],
                        'harga_satuan' => $hargaSatuan,
                        'subtotal'     => $subtotal,
                    ];
                }

                $dp       = $validated['dp'] ?? 0;
                $sisaBayar = $totalBayar - $dp;

                // Simpan transaksi utama
                $transaksi = Transaksi::create([
                    'id_pelanggan'      => $validated['id_pelanggan'],
                    'id_user'           => Auth::id(),
                    'no_nota'           => Transaksi::generateNoNota(),
                    'tanggal'           => $validated['tanggal'],
                    'plat_nomor'        => $validated['plat_nomor'] ?? null,
                    'merk_kendaraan'    => $validated['merk_kendaraan'] ?? null,
                    'total_bayar'       => $totalBayar,
                    'dp'                => $dp,
                    'sisa_bayar'        => max(0, $sisaBayar),
                    'metode_pembayaran' => $validated['metode_pembayaran'],
                    'status_transaksi'  => $validated['status_transaksi'],
                    'catatan'           => $validated['catatan'] ?? null,
                ]);

                // Simpan detail transaksi (bulk insert)
                foreach ($detailItems as &$d) {
                    $d['id_transaksi'] = $transaksi->id_transaksi;
                }
                DetailTransaksi::insert($detailItems);

                // Catat otomatis ke Data Keuangan sebagai pemasukan
                $jmlPemasukan = ($validated['status_transaksi'] === 'Lunas') ? $totalBayar : $dp;
                if ($jmlPemasukan > 0) {
                    DataKeuangan::create([
                        'id_user'      => Auth::id(),
                        'tanggal'      => $validated['tanggal'],
                        'jenis'        => 'pemasukan',
                        'jumlah'       => $jmlPemasukan,
                        'keterangan'   => 'Pembayaran Nota: ' . $transaksi->no_nota . ' (' . $transaksi->status_transaksi . ')',
                        'id_transaksi' => $transaksi->id_transaksi,
                    ]);
                }

                session(['last_transaksi_id' => $transaksi->id_transaksi]);
            });

            return redirect()->route('transaksi.index')
                ->with('success', 'Transaksi berhasil disimpan! Nota: ' . session('last_transaksi_id'));
        } catch (\Throwable $e) {
            return back()->withInput()
                ->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }

    public function show(Transaksi $transaksi)
    {
        $transaksi->load(['pelanggan', 'user', 'details.layanan']);
        return view('transaksi.show', compact('transaksi'));
    }

    public function edit(Transaksi $transaksi)
    {
        $transaksi->load('details');
        $pelanggans = Pelanggan::orderBy('nama')->get();
        $layanans   = Layanan::aktif()->orderBy('nama_layanan')->get();
        return view('transaksi.edit', compact('transaksi', 'pelanggans', 'layanans'));
    }

    public function update(Request $request, Transaksi $transaksi)
    {
        $validated = $request->validate([
            'status_transaksi'  => ['required', 'in:DP,Lunas,Pending'],
            'metode_pembayaran' => ['required', 'in:tunai,transfer,qris'],
            'dp'                => ['nullable', 'numeric', 'min:0'],
            'catatan'           => ['nullable', 'string', 'max:500'],
        ]);

        $dp        = $validated['dp'] ?? $transaksi->dp;
        $sisaBayar = $transaksi->total_bayar - $dp;

        $transaksi->update([
            'status_transaksi'  => $validated['status_transaksi'],
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'dp'                => $dp,
            'sisa_bayar'        => max(0, $sisaBayar),
            'catatan'           => $validated['catatan'],
        ]);

        return redirect()->route('transaksi.show', $transaksi->id_transaksi)
            ->with('success', 'Status transaksi berhasil diperbarui.');
    }

    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();
        return redirect()->route('transaksi.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }

    // Cetak nota (HTML + window.print)
    public function cetakNota(Transaksi $transaksi)
    {
        $transaksi->load(['pelanggan', 'user', 'details.layanan']);
        return view('transaksi.nota', compact('transaksi'));
    }

    // Cetak nota PDF via DomPDF
    public function cetakNotaPdf(Transaksi $transaksi)
    {
        $transaksi->load(['pelanggan', 'user', 'details.layanan']);
        $pdf = Pdf::loadView('transaksi.nota-pdf', compact('transaksi'))
            ->setPaper('a5', 'portrait');
        return $pdf->download('nota-' . $transaksi->no_nota . '.pdf');
    }
}
