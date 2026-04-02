<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\DataKeuangan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $endDate   = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

        // Statistik bulan ini
        $totalPemasukan = DataKeuangan::pemasukan()
            ->dateRange($startDate, $endDate)->sum('jumlah');

        $totalPengeluaran = DataKeuangan::pengeluaran()
            ->dateRange($startDate, $endDate)->sum('jumlah');

        $totalLaba = $totalPemasukan - $totalPengeluaran;

        $totalTransaksi = Transaksi::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)->count();

        $transaksiLunas = Transaksi::where('status_transaksi', 'Lunas')
            ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->count();

        $totalPelanggan = Pelanggan::count();

        // Transaksi terbaru (5 terakhir)
        $transaksiTerbaru = Transaksi::with('pelanggan')
            ->latest()->take(5)->get();

        // Data chart: 6 bulan terakhir untuk grafik pemasukan vs pengeluaran
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date  = now()->subMonths($i);
            $label = $date->translatedFormat('M Y');

            $pemasukan  = DataKeuangan::pemasukan()
                ->whereMonth('tanggal', $date->month)
                ->whereYear('tanggal', $date->year)->sum('jumlah');

            $pengeluaran = DataKeuangan::pengeluaran()
                ->whereMonth('tanggal', $date->month)
                ->whereYear('tanggal', $date->year)->sum('jumlah');

            $chartData[] = [
                'label'       => $label,
                'pemasukan'   => (float) $pemasukan,
                'pengeluaran' => (float) $pengeluaran,
            ];
        }

        // Layanan terlaris bulan ini
        $layananTerlaris = \App\Models\DetailTransaksi::selectRaw(
                'id_layanan, SUM(jumlah) as total_unit, SUM(subtotal) as total_nilai'
            )
            ->whereHas('transaksi', fn($q) =>
                $q->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
            )
            ->with('layanan')
            ->groupBy('id_layanan')
            ->orderByDesc('total_unit')
            ->take(5)->get();

        return view('dashboard', compact(
            'totalPemasukan', 'totalPengeluaran', 'totalLaba',
            'totalTransaksi', 'transaksiLunas', 'totalPelanggan',
            'transaksiTerbaru', 'chartData', 'layananTerlaris',
            'bulan', 'tahun'
        ));
    }
}
