<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Saldo keuangan eksternal / donatur (satu baris). Diisi otomatis oleh SaldoService. */
class PenyimpananKeuanganEksternal extends Model
{
    protected $table = 'penyimpanan_keuangan_eksternal';

    protected $fillable = ['id', 'total_keuangan'];

    protected $casts = ['total_keuangan' => 'decimal:2'];

    public static function saldo(): self
    {
        return static::firstOrCreate(['id' => 1], ['total_keuangan' => 0]);
    }
}
