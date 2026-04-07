<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Pelanggan;
use App\Models\Layanan;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed Users
        User::create([
            'nama'     => 'Admin Body Repair',
            'email'    => 'admin@bodyrepair.com',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
        ]);

        User::create([
            'nama'     => 'Owner Body Repair',
            'email'    => 'owner@bodyrepair.com',
            'password' => Hash::make('password123'),
            'role'     => 'owner',
        ]);

        // Seed Pelanggan
        $pelanggans = [
            ['nama' => 'Budi Santoso',    'alamat' => 'Jl. Merdeka No. 10, Jakarta',   'no_telepon' => '081234567890'],
            ['nama' => 'Siti Rahayu',     'alamat' => 'Jl. Sudirman No. 5, Bandung',   'no_telepon' => '082345678901'],
            ['nama' => 'Ahmad Fauzi',     'alamat' => 'Jl. Diponegoro No. 22, Bekasi', 'no_telepon' => '083456789012'],
            ['nama' => 'Dewi Kusuma',     'alamat' => 'Jl. Hayam Wuruk No. 8, Bogor',  'no_telepon' => '084567890123'],
            ['nama' => 'Eko Prasetyo',    'alamat' => 'Jl. Gatot Subroto No. 15, Depok', 'no_telepon' => '085678901234'],
        ];

        foreach ($pelanggans as $p) {
            Pelanggan::create($p);
        }

        // Seed Layanan Body Repair
        $layanans = [
            ['nama_layanan' => 'Cat Ulang Body',       'harga' => 1500000, 'deskripsi' => 'Pengecatan ulang seluruh bodi kendaraan'],
            ['nama_layanan' => 'Cat Partial Bumper',   'harga' => 500000,  'deskripsi' => 'Pengecatan bagian bumper depan/belakang'],
            ['nama_layanan' => 'Ketok Magic Penyok',   'harga' => 350000,  'deskripsi' => 'Pembetulan penyok ringan tanpa cat'],
            ['nama_layanan' => 'Repair Baret Halus',   'harga' => 200000,  'deskripsi' => 'Penghilangan baret halus pada bodi'],
            ['nama_layanan' => 'Dempul & Cat Lokal',   'harga' => 750000,  'deskripsi' => 'Dempul lokal dan pengecatan ulang area tertentu'],
            ['nama_layanan' => 'Poles Body Full',      'harga' => 300000,  'deskripsi' => 'Poles seluruh bodi kendaraan untuk kilap maksimal'],
            ['nama_layanan' => 'Ganti Spion',          'harga' => 250000,  'deskripsi' => 'Penggantian kaca spion kanan/kiri'],
            ['nama_layanan' => 'Repair Kaca Depan',    'harga' => 600000,  'deskripsi' => 'Perbaikan atau penggantian kaca depan'],
            ['nama_layanan' => 'Cuci Steam',           'harga' => 50000,   'deskripsi' => 'Cuci kendaraan dengan steam pressure'],
            ['nama_layanan' => 'Anti Karat Bawah',     'harga' => 400000,  'deskripsi' => 'Pelapisan anti karat bagian kolong kendaraan'],
        ];

        foreach ($layanans as $l) {
            Layanan::create($l);
        }

        // Seed Data Keuangan
        $this->call(FinanceSeeder::class);
    }
}
