<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regulasi_kas', function (Blueprint $table) {
            $table->id('id_regulasi');
            $table->decimal('nominal', 15, 2)->default(5000);
            $table->date('tanggal_berlaku');
            // tanggal jatuh tempo tiap bulan (default tanggal 10)
            $table->unsignedTinyInteger('hari_jatuh_tempo')->default(10);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regulasi_kas');
    }
};
