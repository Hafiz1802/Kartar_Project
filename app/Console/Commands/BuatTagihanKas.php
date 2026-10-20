<?php

namespace App\Console\Commands;

use App\Models\Pengguna;
use App\Models\RegulasiKas;
use App\Models\TagihanKas;
use Carbon\Carbon;
use Illuminate\Console\Command;

class BuatTagihanKas extends Command
{
    protected $signature = 'kas:buat-tagihan {--periode= : Bulan tagihan format YYYY-MM (default: bulan ini)}';

    protected $description = 'Buat tagihan kas bulanan untuk semua pengguna berdasarkan regulasi yang berlaku';

    public function handle(): int
    {
        $periode = $this->option('periode')
            ? Carbon::createFromFormat('Y-m', $this->option('periode'))->startOfMonth()
            : now()->startOfMonth();

        $regulasi = RegulasiKas::berlakuPada($periode->copy()->endOfMonth());

        if (! $regulasi) {
            $this->error('Belum ada regulasi kas yang berlaku untuk periode ini.');
            return self::FAILURE;
        }

        // Tanggal jatuh tempo tidak boleh melewati akhir bulan (mis. tanggal 31 di bulan Februari).
        $hari = min($regulasi->hari_jatuh_tempo, $periode->daysInMonth);
        $jatuhTempo = $periode->copy()->day($hari);

        $dibuat = 0;
        Pengguna::each(function (Pengguna $p) use ($periode, $regulasi, $jatuhTempo, &$dibuat) {
            $tagihan = TagihanKas::firstOrCreate(
                ['id_pengguna' => $p->id_pengguna, 'periode' => $periode->toDateString()],
                [
                    'id_regulasi' => $regulasi->id_regulasi,
                    'nominal' => $regulasi->nominal,
                    'jatuh_tempo' => $jatuhTempo->toDateString(),
                ]
            );
            $tagihan->wasRecentlyCreated && $dibuat++;
        });

        $this->info("Periode {$periode->format('Y-m')}: {$dibuat} tagihan baru dibuat.");

        return self::SUCCESS;
    }
}
