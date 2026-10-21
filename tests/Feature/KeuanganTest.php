<?php

namespace Tests\Feature;

use App\Models\Donatur;
use App\Models\Pengguna;
use App\Models\PengajuanAnggaran;
use App\Models\RegulasiKas;
use App\Models\TagihanKas;
use App\Services\SaldoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KeuanganTest extends TestCase
{
    use RefreshDatabase;

    private function akun(string $role): Pengguna
    {
        return Pengguna::create(['nama' => ucfirst($role), 'email' => "$role@t.test", 'password' => 'password123',
            'role' => $role, 'no_hp' => '0812', 'alamat' => 'RW 22']);
    }

    public function test_anggota_tidak_boleh_masuk(): void
    {
        $this->actingAs($this->akun('anggota'))->get('/keuangan')->assertForbidden();
        $this->get('/kas')->assertForbidden();
        $this->get('/pengajuan')->assertForbidden();
    }

    public function test_halaman_bendahara_tampil(): void
    {
        $this->actingAs($this->akun('bendahara'));
        foreach (['/keuangan', '/keuangan?tab=donatur', '/keuangan/histori', '/kas', '/pengajuan', '/keuangan/donatur/tambah'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_alur_kas_sampai_pengajuan(): void
    {
        $bendahara = $this->akun('bendahara');
        $ketua = $this->akun('ketua');
        $a = $this->akun('anggota');
        RegulasiKas::create(['nominal' => 5000, 'tanggal_berlaku' => now()->startOfYear(), 'hari_jatuh_tempo' => 10]);

        $this->actingAs($bendahara)->post('/kas/tagihan', ['bulan' => now()->format('Y-m')])->assertRedirect();
        $this->post('/kas/tagihan', ['bulan' => now()->format('Y-m')]); // tidak dobel
        $this->assertSame(1, TagihanKas::count());

        $t = TagihanKas::first();
        $this->post("/kas/tagihan/{$t->id_tagihan}/bayar", ['tanggal_bayar' => now()->toDateString(), 'jumlah_bayar' => 9000, 'metode_pembayaran' => 'tunai'])
            ->assertSessionHas('error'); // melebihi sisa
        $this->post("/kas/tagihan/{$t->id_tagihan}/bayar", ['tanggal_bayar' => now()->toDateString(), 'jumlah_bayar' => 5000, 'metode_pembayaran' => 'tunai'])
            ->assertSessionHas('success');
        $this->assertEquals(5000, SaldoService::saldoInternal());
        $this->post("/kas/tagihan/{$t->id_tagihan}/bayar", ['tanggal_bayar' => now()->toDateString(), 'jumlah_bayar' => 1, 'metode_pembayaran' => 'tunai'])
            ->assertSessionHas('error'); // sudah lunas

        $this->post('/pengajuan', ['keperluan' => 'Spanduk', 'nominal' => 3000])->assertSessionHas('success');
        $p = PengajuanAnggaran::first();
        $this->post("/pengajuan/{$p->id_pengajuan}/setujui")->assertForbidden(); // bendahara tidak boleh memutuskan

        $this->actingAs($ketua)->post("/pengajuan/{$p->id_pengajuan}/setujui")->assertSessionHas('success');
        $this->assertEquals(2000, SaldoService::saldoInternal());

        $this->post('/pengajuan', ['keperluan' => 'X', 'nominal' => 1])->assertForbidden(); // ketua tidak mengajukan
        $this->get('/keuangan')->assertOk()->assertSee('Spanduk');
    }

    public function test_donatur_dan_saldo_tidak_boleh_negatif(): void
    {
        $this->actingAs($this->akun('bendahara'));
        $base = ['tanggal' => now()->toDateString(), 'keterangan' => 'Donasi', 'nominal' => 100000];

        $this->post('/keuangan/donatur', $base + ['status' => 'masuk'])->assertRedirect();
        $this->assertEquals(100000, SaldoService::saldoEksternal());
        $this->post('/keuangan/donatur', ['status' => 'keluar', 'nominal' => 200000] + $base)->assertSessionHasErrors('nominal');

        $d = Donatur::first();
        $this->put("/keuangan/donatur/{$d->id_donatur}", ['status' => 'masuk', 'nominal' => 150000] + $base)->assertRedirect();
        $this->assertEquals(150000, SaldoService::saldoEksternal());
        $this->get('/keuangan?tab=donatur')->assertOk()->assertSee('150.000');
    }
}
