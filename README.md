# 🚗 Sistem Manajemen Layanan Kendaraan

Aplikasi manajemen layanan kendaraan berbasis web yang dibangun menggunakan **Laravel 12**. Dirancang untuk membantu usaha joki/servis kendaraan dalam mengelola pelanggan, transaksi, layanan, dan laporan keuangan secara digital dan efisien.

---

## 📋 Deskripsi Program

**Joki Web** adalah sistem informasi manajemen untuk bisnis layanan kendaraan (joki, servis, detailing, dll). Aplikasi ini menyediakan berbagai fitur utama:

| Fitur | Deskripsi |
|---|---|
| 🏠 **Dashboard** | Ringkasan statistik bulanan: total pemasukan, pengeluaran, laba, jumlah transaksi, dan grafik 6 bulan terakhir |
| 👥 **Manajemen Pelanggan** | Tambah, lihat, edit, dan hapus data pelanggan |
| 🛠️ **Manajemen Layanan** | Kelola daftar layanan beserta harga (aktif/nonaktif) |
| 🧾 **Manajemen Transaksi** | Buat transaksi dengan multiple layanan, kelola status pembayaran (Lunas / DP / Pending), cetak nota HTML & PDF |
| 💰 **Data Keuangan** | Pencatatan otomatis pemasukan dari transaksi, serta pencatatan pengeluaran manual |
| 📊 **Laporan** | Laporan keuangan per periode (pemasukan, pengeluaran, laba bersih) |
| 🔐 **Autentikasi** | Login/logout dengan sistem session Laravel |

### Teknologi yang Digunakan

- **Backend:** PHP 8.2+, Laravel 12
- **Frontend:** Blade Template, Bootstrap / CSS
- **Database:** SQLite (default) atau MySQL
- **PDF:** barryvdh/laravel-dompdf
- **Build Tool:** Vite + Node.js

---

## ⚙️ Cara Instalasi

### Prasyarat

Pastikan perangkat Anda telah terinstal:

- [PHP](https://www.php.net/downloads) >= 8.2
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) >= 18.x & npm
- Git

### Langkah Instalasi

**1. Clone repositori**

```bash
git clone <url-repositori> joki-web
cd joki-web
```

**2. Install dependensi PHP**

```bash
composer install
```

**3. Salin file konfigurasi environment**

```bash
cp .env.example .env
```

**4. Generate application key**

```bash
php artisan key:generate
```

**5. Konfigurasi database**

Buka file `.env` dan sesuaikan pengaturan database. Secara default menggunakan **SQLite**:

```env
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=nama_database
# DB_USERNAME=root
# DB_PASSWORD=
```

Jika ingin menggunakan **MySQL**, ubah menjadi:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=joki_web
DB_USERNAME=root
DB_PASSWORD=password_anda
```

**6. Buat file database SQLite** *(lewati jika menggunakan MySQL)*

```bash
touch database/database.sqlite
# Atau di Windows:
# type nul > database\database.sqlite
```

**7. Jalankan migrasi database**

```bash
php artisan migrate
```

**8. Install dependensi Node.js**

```bash
npm install
```

**9. Build aset frontend**

```bash
npm run build
```

> **Instalasi selesai!** Jalankan aplikasi dengan langkah di bawah.

---

### Instalasi Cepat (Opsional)

Tersedia script otomatis yang menggabungkan semua langkah di atas:

```bash
composer run setup
```

---

## 🚀 Cara Menjalankan Aplikasi

### Mode Development (Direkomendasikan)

Jalankan semua layanan sekaligus (server, queue, log, dan Vite) dengan satu perintah:

```bash
composer run dev
```

Kemudian buka browser dan akses: **[http://localhost:8000](http://localhost:8000)**

### Mode Manual

Jalankan server Laravel dan Vite secara terpisah:

```bash
# Terminal 1 — Laravel server
php artisan serve

# Terminal 2 — Vite dev server (untuk hot-reload aset)
npm run dev
```

---

## 📖 Cara Penggunaan

### 1. Login

Buka aplikasi di browser, kemudian login menggunakan akun yang telah terdaftar. Jika belum ada akun, daftarkan melalui halaman **Register**.

### 2. Dashboard

Setelah login, Anda akan diarahkan ke halaman **Dashboard** yang menampilkan:
- Total pemasukan, pengeluaran, dan laba bulan ini
- Jumlah transaksi dan transaksi lunas
- Total pelanggan terdaftar
- Grafik perbandingan pemasukan vs pengeluaran (6 bulan terakhir)
- Layanan terlaris bulan ini
- 5 transaksi terbaru

Gunakan filter **bulan** dan **tahun** di pojok kanan atas untuk melihat statistik periode lain.

### 3. Manajemen Pelanggan

Menu **Pelanggan** → Tambah, lihat detail, edit, atau hapus data pelanggan.

### 4. Manajemen Layanan

Menu **Layanan** → Kelola daftar layanan yang tersedia beserta harganya. Layanan dapat diaktifkan atau dinonaktifkan.

### 5. Membuat Transaksi Baru

1. Buka menu **Transaksi** → klik **Tambah Transaksi**
2. Pilih **pelanggan** dari daftar
3. Isi **tanggal**, **plat nomor**, dan **merek kendaraan** (opsional)
4. Pilih **layanan** yang diinginkan dan tentukan jumlahnya
5. Tentukan **metode pembayaran** (Tunai / Transfer / QRIS)
6. Tentukan **status pembayaran**:
   - **Lunas** — pembayaran penuh
   - **DP** — bayar sebagian, isi nominal DP
   - **Pending** — belum ada pembayaran
7. Klik **Simpan Transaksi**

### 6. Mencetak Nota Transaksi

Dari halaman detail transaksi, tersedia dua opsi cetak:
- **Cetak Nota (HTML)** — buka halaman nota dan cetak via browser (`Ctrl+P`)
- **Download Nota PDF** — unduh nota dalam format PDF ukuran A5

### 7. Data Keuangan

Menu **Keuangan** → Lihat semua pencatatan keuangan. Pemasukan dari transaksi dicatat **secara otomatis**. Anda juga dapat menambahkan **pengeluaran** secara manual (misalnya: biaya operasional, pembelian bahan, dll).

### 8. Laporan

Menu **Laporan** → Lihat rekapitulasi keuangan berdasarkan rentang tanggal tertentu: total pemasukan, pengeluaran, dan laba bersih.

---

## 🗂️ Struktur Database

| Tabel | Deskripsi |
|---|---|
| `users` | Data pengguna / admin |
| `pelanggans` | Data pelanggan |
| `layanans` | Daftar layanan & harga |
| `transaksis` | Data transaksi utama |
| `detail_transaksis` | Rincian layanan per transaksi |
| `data_keuangans` | Pencatatan pemasukan & pengeluaran |

---

## 🔧 Perintah Artisan Berguna

```bash
# Menjalankan migrasi ulang + seeder
php artisan migrate:fresh --seed

# Membersihkan cache aplikasi
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Membuat akun admin via tinker
php artisan tinker
# >>> \App\Models\User::create(['name'=>'Admin','email'=>'admin@example.com','password'=>bcrypt('password')]);
```

---

## 📄 Lisensi

Proyek ini dibuat untuk keperluan pribadi / pendidikan.
