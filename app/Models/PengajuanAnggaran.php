<?php

namespace App\Models;

use App\Services\SaldoService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class PengajuanAnggaran extends Model
{
    protected $table = 'pengajuan_anggaran';
    protected $primaryKey = 'id_pengajuan';

    protected $fillable = [
        'diajukan_oleh', 'tanggal_pengajuan', 'keperluan', 'nominal', 'status',
        'diputuskan_oleh', 'tanggal_keputusan', 'catatan_ketua', 'bukti_foto',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tanggal_keputusan' => 'date',
        'nominal' => 'decimal:2',
    ];

    public function pengaju(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'diajukan_oleh', 'id_pengguna');
    }

    public function pemutus(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'diputuskan_oleh', 'id_pengguna');
    }

    /**
     * Ketua menyetujui pengajuan. Saldo internal otomatis berkurang (lewat observer).
     * Ditolak dengan exception bila saldo internal tidak cukup.
     */
    public function setujui(Pengguna $ketua, ?string $catatan = null): void
    {
        if (! $ketua->isKetua()) {
            throw new RuntimeException('Hanya ketua yang dapat memutuskan pengajuan.');
        }

        DB::transaction(function () use ($ketua, $catatan) {
            $pengajuan = static::lockForUpdate()->findOrFail($this->getKey());

            if ($pengajuan->status !== 'menunggu') {
                throw new RuntimeException('Pengajuan ini sudah diputuskan.');
            }
            if (SaldoService::saldoInternal() < (float) $pengajuan->nominal) {
                throw new RuntimeException('Saldo kas internal tidak mencukupi.');
            }

            $pengajuan->update([
                'status' => 'disetujui',
                'diputuskan_oleh' => $ketua->getKey(),
                'tanggal_keputusan' => now()->toDateString(),
                'catatan_ketua' => $catatan,
            ]);

            $this->setRawAttributes($pengajuan->getAttributes(), true);
        });
    }

    public function tolak(Pengguna $ketua, ?string $catatan = null): void
    {
        if (! $ketua->isKetua()) {
            throw new RuntimeException('Hanya ketua yang dapat memutuskan pengajuan.');
        }
        if ($this->status !== 'menunggu') {
            throw new RuntimeException('Pengajuan ini sudah diputuskan.');
        }

        $this->update([
            'status' => 'ditolak',
            'diputuskan_oleh' => $ketua->getKey(),
            'tanggal_keputusan' => now()->toDateString(),
            'catatan_ketua' => $catatan,
        ]);
    }
}
