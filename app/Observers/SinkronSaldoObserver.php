<?php

namespace App\Observers;

use App\Models\Donatur;
use App\Services\SaldoService;
use Illuminate\Database\Eloquent\Model;

/** Dipasang pada PembayaranKas, PengajuanAnggaran, dan Donatur. */
class SinkronSaldoObserver
{
    public function saved(Model $model): void   { $this->sinkron($model); }
    public function deleted(Model $model): void { $this->sinkron($model); }

    private function sinkron(Model $model): void
    {
        $model instanceof Donatur
            ? SaldoService::sinkronEksternal()
            : SaldoService::sinkronInternal();
    }
}
