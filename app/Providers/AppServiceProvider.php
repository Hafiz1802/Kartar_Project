<?php

namespace App\Providers;

use App\Models\Donatur;
use App\Models\PembayaranKas;
use App\Models\PengajuanAnggaran;
use App\Observers\SinkronSaldoObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        PembayaranKas::observe(SinkronSaldoObserver::class);
        PengajuanAnggaran::observe(SinkronSaldoObserver::class);
        Donatur::observe(SinkronSaldoObserver::class);
    }
}
