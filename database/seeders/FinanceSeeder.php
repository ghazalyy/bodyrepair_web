<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataKeuangan;
use App\Models\User;

class FinanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mendapatkan ID user pertama (admin) untuk mengisi id_user
        $user = User::first();
        if (!$user) {
            $this->command->error('No user found! Please seed users first.');
            return;
        }

        $id_user = $user->id_user;

        $data = [
            // Tanggal | Keterangan | Jenis | Jumlah
            ['2025-12-01', 'Gaji Karyawan', 'pengeluaran', 24000000.00],
            ['2025-12-01', 'Bonus Karyawan', 'pengeluaran', 5000000.00],
            ['2025-12-01', 'Cat Solid', 'pengeluaran', 240000.00],
            ['2025-12-05', 'Repair Avanza', 'pemasukan', 1700000.00],
            ['2025-12-05', 'Xenia', 'pemasukan', 1800000.00],
            ['2025-12-10', 'Repair HRV', 'pemasukan', 3000000.00],
            ['2025-12-14', 'Fortuner', 'pemasukan', 4500000.00],
            ['2025-12-14', 'Jazz', 'pemasukan', 1300000.00],
            ['2025-12-18', 'Dempul', 'pengeluaran', 170000.00],
            ['2025-12-18', 'Tinner HG', 'pengeluaran', 50000.00],
            ['2025-12-22', 'Repair Brio', 'pemasukan', 1900000.00],
            ['2025-12-22', 'Pickup', 'pemasukan', 1000000.00],
            ['2025-12-26', 'Repair Xpander', 'pemasukan', 4000000.00],
            ['2025-12-26', 'Innova', 'pemasukan', 2200000.00],
            ['2025-12-30', 'Clear PU', 'pengeluaran', 220000.00],
        ];

        foreach ($data as $item) {
            DataKeuangan::create([
                'id_user'    => $id_user,
                'tanggal'    => $item[0],
                'keterangan' => $item[1],
                'jenis'      => $item[2],
                'jumlah'     => $item[3],
            ]);
        }
    }
}
