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
            // ===================== JULI 2025 =====================
            // Tanggal | Keterangan | Jenis | Jumlah
            ['2025-07-01', 'Gaji Karyawan',       'pengeluaran', 24000000.00],
            ['2025-07-01', 'Dempul',              'pengeluaran',   300000.00],
            ['2025-07-01', 'Amplas',              'pengeluaran',    24000.00],
            ['2025-07-01', 'Cat Apios',           'pengeluaran',    50000.00],
            ['2025-07-01', 'Tinner PU',           'pengeluaran',    35000.00],
            ['2025-07-01', 'Tinner HG',           'pengeluaran',    27000.00],
            ['2025-07-01', 'Lakban Kertas',       'pengeluaran',    16000.00],
            ['2025-07-01', 'Epoxy ZKPU Primer',   'pengeluaran',   105000.00],
            ['2025-07-01', 'Clear PU',            'pengeluaran',   190000.00],
            ['2025-07-01', 'Top Collor Rolip',    'pengeluaran',   190000.00],
            ['2025-07-01', 'Body Repair Xenia',   'pemasukan',   2000000.00],
            ['2025-07-01', 'Cat Motor',           'pemasukan',     500000.00],
            ['2025-07-02', 'Repair Avanza',       'pemasukan',   1300000.00],
            ['2025-07-02', 'Brio',                'pemasukan',   1500000.00],
            ['2025-07-03', 'Angkot',              'pemasukan',     500000.00],
            ['2025-07-03', 'Cat Motor',           'pemasukan',     500000.00],
            ['2025-07-04', 'Repair HRV',          'pemasukan',   2500000.00],
            ['2025-07-05', 'Repair Xenia',        'pemasukan',   1500000.00],
            ['2025-07-05', 'Avanza',              'pemasukan',   1000000.00],
            ['2025-07-05', 'Inova',               'pemasukan',   2500000.00],
            ['2025-07-05', 'Dempul Autoglow',     'pengeluaran',   150000.00],
            ['2025-07-05', 'Dempul Alfagloss',    'pengeluaran',   120000.00],
            ['2025-07-08', 'Amplas',              'pengeluaran',    24000.00],
            ['2025-07-08', 'Cat Solid',           'pengeluaran',   190000.00],
            ['2025-07-08', 'Rubbing Compund',     'pengeluaran',    60000.00],
            ['2025-07-08', 'Teroron Sieler',      'pengeluaran',    90000.00],
            ['2025-07-08', 'Cat Moge',            'pemasukan',   1000000.00],
            ['2025-07-08', 'Repair Rush',         'pemasukan',   1700000.00],
            ['2025-07-08', 'Jazz',                'pemasukan',   1000000.00],
            ['2025-07-08', 'Fortunner',           'pemasukan',   4000000.00],
            ['2025-07-13', 'Cat Motor PCX',       'pemasukan',     400000.00],
            ['2025-07-13', 'Angkutan',            'pemasukan',     800000.00],
            ['2025-07-15', 'Repair Repair',       'pemasukan',   2500000.00],
            ['2025-07-16', 'Repair Brio',         'pemasukan',   1500000.00],
            ['2025-07-16', 'Repair Pickup',       'pemasukan',     800000.00],
            ['2025-07-20', 'Repair Ayla',         'pemasukan',   1000000.00],
            ['2025-07-20', 'Terios',              'pemasukan',   1200000.00],
            ['2025-07-21', 'Dempul',              'pengeluaran',   115000.00],
            ['2025-07-21', 'Cat Laba-laba',       'pengeluaran',   190000.00],
            ['2025-07-21', 'Cat Oplos',           'pengeluaran',   150000.00],
            ['2025-07-21', 'Amplas',              'pengeluaran',    50000.00],
            ['2025-07-21', 'Jerigen',             'pengeluaran',    15000.00],
            ['2025-07-22', 'Repaint HRV',         'pemasukan',     400000.00],
            ['2025-07-22', 'Ketok Magic Brio',    'pemasukan',   1500000.00],
            ['2025-07-22', 'Repaint Xpander',     'pemasukan',   4500000.00],
            ['2025-07-23', 'Repair Brio',         'pemasukan',   3500000.00],
            ['2025-07-23', 'CRV',                 'pemasukan',   1500000.00],
            ['2025-07-23', 'Jazz',                'pemasukan',   1000000.00],
            ['2025-07-24', 'Rubbing Compund',     'pengeluaran',   120000.00],
            ['2025-07-24', 'Cat Laba-Laba',       'pengeluaran',   360000.00],
            ['2025-07-24', 'Cat Opior',           'pengeluaran',   300000.00],
            ['2025-07-24', 'Clear PU',            'pengeluaran',   180000.00],
            ['2025-07-24', 'Repair Moge',         'pemasukan',   1500000.00],
            ['2025-07-24', 'Avanza',              'pemasukan',   1300000.00],
            ['2025-07-25', 'Repair Fortuner',     'pemasukan',   6500000.00],
            ['2025-07-25', 'Xenia',               'pemasukan',   1800000.00],
            ['2025-07-25', 'Cat Motor',           'pemasukan',     500000.00],
            ['2025-07-26', 'Repair Xpander',      'pemasukan',   3500000.00],
            ['2025-07-26', 'Inova',               'pemasukan',   1800000.00],
            ['2025-07-26', 'Grandmax',            'pemasukan',   1200000.00],
            ['2025-07-26', 'Wuling Airru',        'pemasukan',   1200000.00],
            ['2025-07-26', 'Ayla',                'pemasukan',   1000000.00],

            // ===================== AGUSTUS 2025 =====================
            // Tanggal | Keterangan | Jenis | Jumlah
            ['2025-08-01', 'Gaji Karyawan',      'pengeluaran', 24000000.00],
            ['2025-08-01', 'Dempul',              'pengeluaran',   250000.00],
            ['2025-08-01', 'Amplas',              'pengeluaran',    90000.00],
            ['2025-08-01', 'Cat PU',              'pengeluaran',   200000.00],
            ['2025-08-01', 'Body Repair Avanza',  'pemasukan',   2200000.00],
            ['2025-08-01', 'Cat Motor',           'pemasukan',     600000.00],
            ['2025-08-03', 'Repair Xenia',        'pemasukan',   1800000.00],
            ['2025-08-03', 'Brio',                'pemasukan',   1400000.00],
            ['2025-08-05', 'Repair HRV',          'pemasukan',   2700000.00],
            ['2025-08-08', 'Repair Jazz',         'pemasukan',   1200000.00],
            ['2025-08-08', 'Fortuner',            'pemasukan',   3800000.00],
            ['2025-08-08', 'Cat Solid',           'pengeluaran',   220000.00],
            ['2025-08-08', 'Rubbing Compound',    'pengeluaran',    80000.00],
            ['2025-08-12', 'Repair Rush',         'pemasukan',   1900000.00],
            ['2025-08-12', 'Cat Motor PCX',       'pemasukan',     500000.00],
            ['2025-08-15', 'Repair Brio',         'pemasukan',   1600000.00],
            ['2025-08-15', 'Repair Pickup',       'pemasukan',     900000.00],
            ['2025-08-20', 'Repair Ayla',         'pemasukan',   1100000.00],
            ['2025-08-20', 'Terios',              'pemasukan',   1300000.00],
            ['2025-08-25', 'Repair Fortuner',     'pemasukan',   6000000.00],
            ['2025-08-25', 'Xenia',               'pemasukan',   1700000.00],
            ['2025-08-28', 'Dempul',              'pengeluaran',   140000.00],
            ['2025-08-28', 'Clear PU',            'pengeluaran',   200000.00],

            // ===================== SEPTEMBER 2025 =====================
            ['2025-09-01', 'Gaji Karyawan',   'pengeluaran', 24000000.00],
            ['2025-09-01', 'Cat PU',          'pengeluaran',   110000.00],
            ['2025-09-01', 'Tinner HG',       'pengeluaran',    40000.00],
            ['2025-09-03', 'Repair Avanza',   'pemasukan',   1500000.00],
            ['2025-09-03', 'Brio',            'pemasukan',   1500000.00],
            ['2025-09-07', 'Repair HRV',      'pemasukan',   2300000.00],
            ['2025-09-10', 'Cat Motor',       'pemasukan',     700000.00],
            ['2025-09-10', 'Repair Rush',     'pemasukan',   2000000.00],
            ['2025-09-14', 'Fortuner',        'pemasukan',   4200000.00],
            ['2025-09-14', 'Jazz',            'pemasukan',   1100000.00],
            ['2025-09-18', 'Repair Brio',     'pemasukan',   1700000.00],
            ['2025-09-18', 'Repair Pickup',   'pemasukan',     350000.00],
            ['2025-09-22', 'Dempul',          'pengeluaran',   130000.00],
            ['2025-09-22', 'Amplas',          'pengeluaran',    35000.00],
            ['2025-09-26', 'Repair Xpander',  'pemasukan',   3600000.00],
            ['2025-09-26', 'Innova',          'pemasukan',   2000000.00],
            ['2025-09-29', 'Clear PU',        'pengeluaran',   190000.00],

            // ===================== OKTOBER 2025 =====================
            ['2025-10-01', 'Gaji Karyawan',   'pengeluaran', 24000000.00],
            ['2025-10-01', 'Cat Solid',       'pengeluaran',   230000.00],
            ['2025-10-04', 'Repair Xenia',    'pemasukan',   1900000.00],
            ['2025-10-04', 'Avanza',          'pemasukan',   1300000.00],
            ['2025-10-08', 'Repair HRV',      'pemasukan',   2600000.00],
            ['2025-10-12', 'Repair Jazz',     'pemasukan',   1300000.00],
            ['2025-10-12', 'Fortuner',        'pemasukan',   4000000.00],
            ['2025-10-16', 'Dempul',          'pengeluaran',   150000.00],
            ['2025-10-16', 'Tinner PU',       'pengeluaran',    45000.00],
            ['2025-10-20', 'Repair Ayla',     'pemasukan',   1200000.00],
            ['2025-10-20', 'Terios',          'pemasukan',   1400000.00],
            ['2025-10-24', 'Repair Brio',     'pemasukan',   1800000.00],
            ['2025-10-24', 'Pickup',          'pemasukan',     900000.00],
            ['2025-10-28', 'Repair Fortuner', 'pemasukan',   6200000.00],

            // ===================== NOVEMBER 2025 =====================
            ['2025-11-01', 'Gaji Karyawan',   'pengeluaran', 24000000.00],
            ['2025-11-01', 'Cat PU',          'pengeluaran',   220000.00],
            ['2025-11-03', 'Repair Avanza',   'pemasukan',   1600000.00],
            ['2025-11-03', 'Brio',            'pemasukan',   1500000.00],
            ['2025-11-07', 'Repair HRV',      'pemasukan',   2900000.00],
            ['2025-11-11', 'Fortuner',        'pemasukan',   4300000.00],
            ['2025-11-11', 'Jazz',            'pemasukan',   1200000.00],
            ['2025-11-15', 'Dempul',          'pengeluaran',   160000.00],
            ['2025-11-15', 'Amplas',          'pengeluaran',    40000.00],
            ['2025-11-20', 'Repair Rush',     'pemasukan',   2100000.00],
            ['2025-11-20', 'Cat Motor',       'pemasukan',     600000.00],
            ['2025-11-24', 'Repair Xpander',  'pemasukan',   3800000.00],
            ['2025-11-24', 'Innova',          'pemasukan',   2100000.00],
            ['2025-11-28', 'Clear PU',        'pengeluaran',   210000.00],

            // ===================== DESEMBER 2025 =====================
            ['2025-12-01', 'Gaji Karyawan',   'pengeluaran', 24000000.00],
            ['2025-12-01', 'Bonus Karyawan',  'pengeluaran',  5000000.00],
            ['2025-12-01', 'Cat Solid',       'pengeluaran',   240000.00],
            ['2025-12-05', 'Repair Avanza',   'pemasukan',   1700000.00],
            ['2025-12-05', 'Xenia',           'pemasukan',   1800000.00],
            ['2025-12-10', 'Repair HRV',      'pemasukan',   3000000.00],
            ['2025-12-14', 'Fortuner',        'pemasukan',   4500000.00],
            ['2025-12-14', 'Jazz',            'pemasukan',   1300000.00],
            ['2025-12-18', 'Dempul',          'pengeluaran',   170000.00],
            ['2025-12-18', 'Tinner HG',       'pengeluaran',    50000.00],
            ['2025-12-22', 'Repair Brio',     'pemasukan',   1900000.00],
            ['2025-12-22', 'Pickup',          'pemasukan',   1000000.00],
            ['2025-12-26', 'Repair Xpander',  'pemasukan',   4000000.00],
            ['2025-12-26', 'Innova',          'pemasukan',   2200000.00],
            ['2025-12-30', 'Clear PU',        'pengeluaran',   220000.00],
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
