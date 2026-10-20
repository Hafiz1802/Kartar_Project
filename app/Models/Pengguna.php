<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class Pengguna extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'pengguna';
    protected $primaryKey = 'id_pengguna';

    protected $fillable = ['nama', 'email', 'password', 'role', 'no_hp', 'alamat'];

    protected $hidden = ['password', 'remember_token'];

    /** Hash otomatis, tetapi tidak di-hash dua kali jika sudah berupa hash. */
    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password'] = Hash::info($value)['algoName'] === 'unknown'
            ? Hash::make($value)
            : $value;
    }

    public function isKetua(): bool     { return $this->role === 'ketua'; }
    public function isBendahara(): bool { return $this->role === 'bendahara'; }
    public function isAnggota(): bool   { return $this->role === 'anggota'; }

    /** Ketua dan bendahara boleh mengelola keuangan (CRU). */
    public function bolehKelolaKeuangan(): bool
    {
        return in_array($this->role, ['ketua', 'bendahara'], true);
    }

    public function tagihan(): HasMany
    {
        return $this->hasMany(TagihanKas::class, 'id_pengguna', 'id_pengguna');
    }

    public function pembayaranDicatat(): HasMany
    {
        return $this->hasMany(PembayaranKas::class, 'dicatat_oleh', 'id_pengguna');
    }

    public function pengajuan(): HasMany
    {
        return $this->hasMany(PengajuanAnggaran::class, 'diajukan_oleh', 'id_pengguna');
    }

    public function pengajuanDiputuskan(): HasMany
    {
        return $this->hasMany(PengajuanAnggaran::class, 'diputuskan_oleh', 'id_pengguna');
    }

    public function donaturDicatat(): HasMany
    {
        return $this->hasMany(Donatur::class, 'dicatat_oleh', 'id_pengguna');
    }
}
