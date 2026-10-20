<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donatur', function (Blueprint $table) {
            $table->id('id_donatur');
            $table->foreignId('dicatat_oleh')->nullable()->constrained('pengguna', 'id_pengguna')->nullOnDelete();
            $table->string('nama_donatur', 100)->nullable();
            $table->enum('status', ['masuk', 'keluar']);
            $table->date('tanggal');
            $table->string('keterangan', 255);
            $table->decimal('nominal', 15, 2);
            $table->string('bukti_foto', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donatur');
    }
};
