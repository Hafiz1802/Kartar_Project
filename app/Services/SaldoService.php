<?php

namespace App\Services;

use App\Models\Donatur;
use App\Models\PembayaranKas;
use App\Models\PengajuanAnggaran;
use App\Models\PenyimpananKeuanganEksternal;
use App\Models\PenyimpananKeuanganInternal;

/**
 * Saldo dihitung ulang dari data transaksi (bukan ditambah/dikurangi manual),
 * sehingga tetap akurat walaupun transaksi diedit atau dihapus.
 *
 *  Internal = SUM(pembayaran_kas lunas) - SUM(pengajuan_anggaran disetujui)
 *  Eksternal = SUM(donatur masuk) - SUM(donatur keluar)
 */
class SaldoService
{
    public static function sinkronInternal(): float
    {
        $masuk = (float) PembayaranKas::where('status_bayar', 'lunas')->sum('jumlah_bayar');
        $keluar = (float) PengajuanAnggaran::where('status', 'disetujui')->sum('nominal');
        $total = $masuk - $keluar;

        PenyimpananKeuanganInternal::saldo()->update(['total_keuangan_internal' => $total]);

        return $total;
    }

    public static function sinkronEksternal(): float
    {
        $masuk = (float) Donatur::where('status', 'masuk')->sum('nominal');
        $keluar = (float) Donatur::where('status', 'keluar')->sum('nominal');
        $total = $masuk - $keluar;

        PenyimpananKeuanganEksternal::saldo()->update(['total_keuangan' => $total]);

        return $total;
    }

    public static function saldoInternal(): float
    {
        return (float) PenyimpananKeuanganInternal::saldo()->total_keuangan_internal;
    }

    public static function saldoEksternal(): float
    {
        return (float) PenyimpananKeuanganEksternal::saldo()->total_keuangan;
    }
}
