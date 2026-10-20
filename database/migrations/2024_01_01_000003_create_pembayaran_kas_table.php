<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran_kas', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->foreignId('id_tagihan')->constrained('tagihan_kas', 'id_tagihan')->restrictOnDelete();
            $table->foreignId('dicatat_oleh')->constrained('pengguna', 'id_pengguna')->restrictOnDelete();
            $table->date('tanggal_bayar');
            $table->decimal('jumlah_bayar', 15, 2)->default(0);
            $table->enum('metode_pembayaran', ['tunai', 'transfer']);
            $table->enum('status_bayar', ['lunas', 'belum'])->default('belum');
            $table->string('bukti_foto', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_kas');
    }
};
