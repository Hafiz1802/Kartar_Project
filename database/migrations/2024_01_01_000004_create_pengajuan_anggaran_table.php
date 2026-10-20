<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_anggaran', function (Blueprint $table) {
            $table->id('id_pengajuan');
            $table->foreignId('diajukan_oleh')->constrained('pengguna', 'id_pengguna')->restrictOnDelete();
            $table->date('tanggal_pengajuan');
            $table->string('keperluan', 255);
            $table->decimal('nominal', 15, 2);
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->foreignId('diputuskan_oleh')->nullable()->constrained('pengguna', 'id_pengguna')->nullOnDelete();
            $table->date('tanggal_keputusan')->nullable();
            $table->string('catatan_ketua', 255)->nullable();
            $table->string('bukti_foto', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_anggaran');
    }
};
