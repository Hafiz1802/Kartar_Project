<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use App\Models\RegulasiKas;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun awal. GANTI PASSWORD setelah login pertama!
        Pengguna::updateOrCreate(['email' => 'ketua@kartar.test'], [
            'nama' => 'Ketua Karang Taruna', 'password' => 'password', 'role' => 'ketua',
            'no_hp' => '081200000001', 'alamat' => 'RW 022',
        ]);
        Pengguna::updateOrCreate(['email' => 'bendahara@kartar.test'], [
            'nama' => 'Bendahara Karang Taruna', 'password' => 'password', 'role' => 'bendahara',
            'no_hp' => '081200000002', 'alamat' => 'RW 022',
        ]);
        Pengguna::updateOrCreate(['email' => 'anggota@kartar.test'], [
            'nama' => 'Anggota Contoh', 'password' => 'password', 'role' => 'anggota',
            'no_hp' => '081200000003', 'alamat' => 'RW 022',
        ]);

        // Regulasi default: Rp 5.000, jatuh tempo tanggal 10
        RegulasiKas::firstOrCreate(
            ['tanggal_berlaku' => now()->startOfYear()->toDateString()],
            ['nominal' => 5000, 'hari_jatuh_tempo' => 10]
        );
    }
}
