<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranKas extends Model
{
    protected $table = 'pembayaran_kas';
    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'id_tagihan', 'dicatat_oleh', 'tanggal_bayar', 'jumlah_bayar',
        'metode_pembayaran', 'status_bayar', 'bukti_foto',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'jumlah_bayar' => 'decimal:2',
    ];

    public function tagihan(): BelongsTo
    {
        return $this->belongsTo(TagihanKas::class, 'id_tagihan', 'id_tagihan');
    }

    public function pencatat(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'dicatat_oleh', 'id_pengguna');
    }
}
