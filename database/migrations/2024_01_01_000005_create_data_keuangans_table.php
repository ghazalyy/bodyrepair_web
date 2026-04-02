<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_keuangans', function (Blueprint $table) {
            $table->id('id_keuangan');
            $table->unsignedBigInteger('id_user');
            $table->date('tanggal');
            $table->enum('jenis', ['pemasukan', 'pengeluaran']);
            $table->decimal('jumlah', 15, 2)->default(0);
            $table->string('keterangan', 255);
            $table->unsignedBigInteger('id_transaksi')->nullable()
                ->comment('Jika pemasukan dari transaksi, referensi ke tabel transaksis');
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('users');
            $table->foreign('id_transaksi')->references('id_transaksi')->on('transaksis')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_keuangans');
    }
};
