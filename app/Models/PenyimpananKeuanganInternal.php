<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Saldo kas internal (satu baris). Diisi otomatis oleh SaldoService. */
class PenyimpananKeuanganInternal extends Model
{
    protected $table = 'penyimpanan_keuangan_internal';

    protected $fillable = ['id', 'total_keuangan_internal'];

    protected $casts = ['total_keuangan_internal' => 'decimal:2'];

    public static function saldo(): self
    {
        return static::firstOrCreate(['id' => 1], ['total_keuangan_internal' => 0]);
    }
}
