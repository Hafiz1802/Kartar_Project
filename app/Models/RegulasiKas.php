<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;

class RegulasiKas extends Model
{
    protected $table = 'regulasi_kas';
    protected $primaryKey = 'id_regulasi';

    protected $fillable = ['nominal', 'tanggal_berlaku', 'hari_jatuh_tempo'];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tanggal_berlaku' => 'date',
        'hari_jatuh_tempo' => 'integer',
    ];

    /** Regulasi yang berlaku pada tanggal tertentu (yang terbaru dengan tanggal_berlaku <= tanggal). */
    public static function berlakuPada(CarbonInterface $tanggal): ?self
    {
        return static::whereDate('tanggal_berlaku', '<=', $tanggal->toDateString())
            ->orderByDesc('tanggal_berlaku')
            ->orderByDesc('id_regulasi')
            ->first();
    }
}
