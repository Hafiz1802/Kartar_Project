<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penyimpanan_keuangan_internal', function (Blueprint $table) {
            $table->id();
            $table->decimal('total_keuangan_internal', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('penyimpanan_keuangan_eksternal', function (Blueprint $table) {
            $table->id();
            $table->decimal('total_keuangan', 15, 2)->default(0);
            $table->timestamps();
        });

        // Masing-masing tabel hanya berisi SATU baris (saldo tunggal).
        $now = now();
        DB::table('penyimpanan_keuangan_internal')->insert(['id' => 1, 'total_keuangan_internal' => 0, 'created_at' => $now, 'updated_at' => $now]);
        DB::table('penyimpanan_keuangan_eksternal')->insert(['id' => 1, 'total_keuangan' => 0, 'created_at' => $now, 'updated_at' => $now]);
    }

    public function down(): void
    {
        Schema::dropIfExists('penyimpanan_keuangan_eksternal');
        Schema::dropIfExists('penyimpanan_keuangan_internal');
    }
};
