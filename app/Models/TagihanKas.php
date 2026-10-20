<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TagihanKas extends Model
{
    protected $table = 'tagihan_kas';
    protected $primaryKey = 'id_tagihan';

    protected $fillable = ['id_pengguna', 'id_regulasi', 'nominal', 'periode', 'jatuh_tempo'];

    protected $casts = [
        'nominal' => 'decimal:2',
        'periode' => 'date',
        'jatuh_tempo' => 'date',
    ];

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function regulasi(): BelongsTo
    {
        return $this->belongsTo(RegulasiKas::class, 'id_regulasi', 'id_regulasi');
    }

    public function pembayaran(): HasMany
    {
        return $this->hasMany(PembayaranKas::class, 'id_tagihan', 'id_tagihan');
    }

    /** Total pembayaran berstatus lunas untuk tagihan ini. */
    public function totalDibayar(): float
    {
        return (float) $this->pembayaran()->where('status_bayar', 'lunas')->sum('jumlah_bayar');
    }

    public function sudahLunas(): bool
    {
        return $this->totalDibayar() >= (float) $this->nominal;
    }

    public function terlambat(): bool
    {
        return ! $this->sudahLunas() && $this->jatuh_tempo->isPast();
    }
}
