<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihan_kas', function (Blueprint $table) {
            $table->id('id_tagihan');
            $table->foreignId('id_pengguna')->constrained('pengguna', 'id_pengguna')->restrictOnDelete();
            $table->foreignId('id_regulasi')->nullable()->constrained('regulasi_kas', 'id_regulasi')->nullOnDelete();
            // salinan nominal regulasi saat tagihan dibuat, agar tagihan lama tidak berubah
            $table->decimal('nominal', 15, 2);
            $table->date('periode');      // selalu tanggal 1 pada bulan tagihan
            $table->date('jatuh_tempo');
            $table->timestamps();

            // satu pengguna hanya punya satu tagihan per bulan
            $table->unique(['id_pengguna', 'periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihan_kas');
    }
};
