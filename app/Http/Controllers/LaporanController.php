<?php

namespace App\Http\Controllers;

use App\Models\DataKeuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $dari   = $request->get('dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('sampai', now()->endOfMonth()->format('Y-m-d'));
        $jenis  = $request->get('jenis', '');

        $query = DataKeuangan::with(['user', 'transaksi'])
            ->dateRange($dari, $sampai)
            ->when($jenis, fn($q) => $q->where('jenis', $jenis))
            ->orderBy('tanggal', 'desc');

        $dataKeuangans = $query->paginate(20)->withQueryString();

        $totalPemasukan  = DataKeuangan::pemasukan()->dateRange($dari, $sampai)->sum('jumlah');
        $totalPengeluaran = DataKeuangan::pengeluaran()->dateRange($dari, $sampai)->sum('jumlah');
        $totalLaba       = $totalPemasukan - $totalPengeluaran;

        // Ringkasan per hari untuk chart mini
        $harianData = DataKeuangan::selectRaw(
                'DATE(tanggal) as tgl,
                 SUM(CASE WHEN jenis="pemasukan" THEN jumlah ELSE 0 END) as pemasukan,
                 SUM(CASE WHEN jenis="pengeluaran" THEN jumlah ELSE 0 END) as pengeluaran'
            )
            ->dateRange($dari, $sampai)
            ->groupBy('tgl')
            ->orderBy('tgl')
            ->get();

        return view('laporan.index', compact(
            'dataKeuangans', 'totalPemasukan', 'totalPengeluaran', 'totalLaba',
            'dari', 'sampai', 'jenis', 'harianData'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tanggal'    => ['required', 'date'],
            'jenis'      => ['required', 'in:pemasukan,pengeluaran'],
            'jumlah'     => ['required', 'numeric', 'min:1'],
            'keterangan' => ['required', 'string', 'max:255'],
        ], [
            'tanggal.required'    => 'Tanggal wajib diisi.',
            'jenis.required'      => 'Jenis keuangan wajib dipilih.',
            'jumlah.required'     => 'Jumlah wajib diisi.',
            'keterangan.required' => 'Keterangan wajib diisi.',
        ]);

        $data['id_user'] = Auth::id();
        DataKeuangan::create($data);

        return redirect()->route('laporan.index')
            ->with('success', 'Data keuangan berhasil ditambahkan.');
    }

    public function destroy(DataKeuangan $laporan)
    {
        // Jangan hapus yang berasal dari transaksi otomatis
        if ($laporan->id_transaksi) {
            return back()->with('error', 'Data ini terhubung dengan transaksi dan tidak bisa dihapus langsung.');
        }
        $laporan->delete();
        return redirect()->route('laporan.index')
            ->with('success', 'Data keuangan berhasil dihapus.');
    }

    // Cetak laporan PDF
    public function cetakPdf(Request $request)
    {
        $dari   = $request->get('dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('sampai', now()->endOfMonth()->format('Y-m-d'));

        $dataKeuangans   = DataKeuangan::with('user')->dateRange($dari, $sampai)->orderBy('tanggal')->get();
        $totalPemasukan  = $dataKeuangans->where('jenis', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = $dataKeuangans->where('jenis', 'pengeluaran')->sum('jumlah');
        $totalLaba       = $totalPemasukan - $totalPengeluaran;

        $pdf = Pdf::loadView('laporan.pdf', compact(
            'dataKeuangans', 'totalPemasukan', 'totalPengeluaran', 'totalLaba', 'dari', 'sampai'
        ))->setPaper('a4', 'landscape');

        return $pdf->stream('laporan-keuangan-' . $dari . '-sd-' . $sampai . '.pdf');
    }

    // Rekap transaksi (khusus Owner)
    public function rekapTransaksi(Request $request)
    {
        $dari   = $request->get('dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->get('sampai', now()->endOfMonth()->format('Y-m-d'));

        $transaksis = \App\Models\Transaksi::with('pelanggan')
            ->whereDate('tanggal', '>=', $dari)
            ->whereDate('tanggal', '<=', $sampai)
            ->orderBy('tanggal', 'desc')
            ->paginate(20)->withQueryString();

        $totalNilai  = \App\Models\Transaksi::whereDate('tanggal', '>=', $dari)
            ->whereDate('tanggal', '<=', $sampai)->sum('total_bayar');

        $totalLunas  = \App\Models\Transaksi::where('status_transaksi', 'Lunas')
            ->whereDate('tanggal', '>=', $dari)->whereDate('tanggal', '<=', $sampai)->count();

        $totalPending = \App\Models\Transaksi::where('status_transaksi', '!=', 'Lunas')
            ->whereDate('tanggal', '>=', $dari)->whereDate('tanggal', '<=', $sampai)->count();

        return view('laporan.rekap-transaksi', compact(
            'transaksis', 'totalNilai', 'totalLunas', 'totalPending', 'dari', 'sampai'
        ));
    }
}
