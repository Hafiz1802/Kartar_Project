<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donatur extends Model
{
    protected $table = 'donatur';
    protected $primaryKey = 'id_donatur';

    protected $fillable = [
        'dicatat_oleh', 'nama_donatur', 'status', 'tanggal',
        'keterangan', 'nominal', 'bukti_foto',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
    ];

    public function pencatat(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'dicatat_oleh', 'id_pengguna');
    }
}
