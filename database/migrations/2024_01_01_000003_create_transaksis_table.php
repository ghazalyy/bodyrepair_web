<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->unsignedBigInteger('id_pelanggan');
            $table->unsignedBigInteger('id_user');
            $table->string('no_nota', 30)->unique();
            $table->date('tanggal');
            $table->string('plat_nomor', 20)->nullable();
            $table->string('merk_kendaraan', 100)->nullable();
            $table->decimal('total_bayar', 15, 2)->default(0);
            $table->decimal('dp', 15, 2)->default(0);
            $table->decimal('sisa_bayar', 15, 2)->default(0);
            $table->enum('metode_pembayaran', ['tunai', 'transfer', 'qris'])->default('tunai');
            $table->enum('status_transaksi', ['DP', 'Lunas', 'Pending'])->default('Pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggans');
            $table->foreign('id_user')->references('id_user')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
