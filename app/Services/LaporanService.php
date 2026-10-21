<?php

namespace App\Services;

use App\Models\Donatur;
use App\Models\PembayaranKas;
use App\Models\PengajuanAnggaran;
use Illuminate\Support\Collection;

/** Menyusun daftar transaksi dan rekap untuk halaman Keuangan serta Histori & Cetak. */
class LaporanService
{
    /** Transaksi kas internal: pembayaran kas lunas (masuk) + pengajuan disetujui (keluar). */
    public static function internal(int $tahun): Collection
    {
        $masuk = PembayaranKas::with('tagihan.pengguna')
            ->where('status_bayar', 'lunas')
            ->whereYear('tanggal_bayar', $tahun)
            ->get()
            ->map(fn ($p) => [
                'tanggal' => $p->tanggal_bayar,
                'keterangan' => 'Kas ' . ($p->tagihan?->pengguna?->nama ?? '-')
                    . ($p->tagihan ? ' ' . $p->tagihan->periode->format('m/Y') : '')
                    . ' (' . $p->metode_pembayaran . ')',
                'masuk' => (float) $p->jumlah_bayar,
                'keluar' => 0.0,
            ]);

        $keluar = PengajuanAnggaran::where('status', 'disetujui')
            ->whereYear('tanggal_keputusan', $tahun)
            ->get()
            ->map(fn ($a) => [
                'tanggal' => $a->tanggal_keputusan,
                'keterangan' => $a->keperluan,
                'masuk' => 0.0,
                'keluar' => (float) $a->nominal,
            ]);

        return $masuk->concat($keluar)->sortByDesc(fn ($r) => $r['tanggal']->timestamp)->values();
    }

    public static function donatur(int $tahun): Collection
    {
        return Donatur::whereYear('tanggal', $tahun)->orderByDesc('tanggal')->orderByDesc('id_donatur')->get();
    }

    /** @return array<int, array{masuk: float, keluar: float}> indeks bulan 1..12 (internal + donatur) */
    public static function rekapBulanan(int $tahun): array
    {
        $hasil = array_fill(1, 12, ['masuk' => 0.0, 'keluar' => 0.0]);

        foreach (self::internal($tahun) as $r) {
            $b = (int) $r['tanggal']->format('n');
            $hasil[$b]['masuk'] += $r['masuk'];
            $hasil[$b]['keluar'] += $r['keluar'];
        }
        foreach (self::donatur($tahun) as $d) {
            $b = (int) $d->tanggal->format('n');
            $d->status === 'masuk'
                ? $hasil[$b]['masuk'] += (float) $d->nominal
                : $hasil[$b]['keluar'] += (float) $d->nominal;
        }

        return $hasil;
    }

    /** Daftar tahun yang punya data (terbaru dulu), minimal tahun berjalan. */
    public static function daftarTahun(): array
    {
        $awal = collect([
            PembayaranKas::min('tanggal_bayar'),
            PengajuanAnggaran::min('tanggal_keputusan'),
            Donatur::min('tanggal'),
        ])->filter()->map(fn ($t) => (int) substr((string) $t, 0, 4))->min() ?? (int) now()->year;

        return range((int) now()->year, min($awal, (int) now()->year));
    }

    public static function rekapTahunan(): array
    {
        $baris = [];
        foreach (array_reverse(self::daftarTahun()) as $tahun) {
            $int = self::internal($tahun);
            $don = self::donatur($tahun);
            $baris[] = [
                'tahun' => $tahun,
                'internal_masuk' => $int->sum('masuk'),
                'internal_keluar' => $int->sum('keluar'),
                'donatur_masuk' => (float) $don->where('status', 'masuk')->sum('nominal'),
                'donatur_keluar' => (float) $don->where('status', 'keluar')->sum('nominal'),
            ];
        }

        return $baris;
    }
}
