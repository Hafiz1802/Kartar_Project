<?php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\KasBulananController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\PengajuanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
Route::get('/', fn () => Auth::check() ? redirect()->route('dashboard') : redirect()->route('login'));
Route::middleware('guest')->group(function () {
    Route::get('/login', fn () => view('auth.login'))->name('login');
    Route::post('/login', function (Request $request) {
        $credentials = $request->validate(['email' => ['required', 'email'], 'password' => ['required', 'string']]);
        if (Auth::attempt($credentials)) { $request->session()->regenerate(); return redirect()->intended(route('dashboard')); }
        return back()->withErrors(['email' => 'Email atau kata sandi tidak sesuai.'])->onlyInput('email');
    })->name('login.process');
});
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::prefix('ketua/anggota')->name('ketua.anggota.')->group(function () {
        Route::get('/', [AnggotaController::class, 'index'])->name('index');
        Route::get('/tambah', [AnggotaController::class, 'create'])->name('create');
        Route::post('/', [AnggotaController::class, 'store'])->name('store');
        Route::get('/{pengguna}/edit', [AnggotaController::class, 'edit'])->name('edit');
        Route::put('/{pengguna}', [AnggotaController::class, 'update'])->name('update');
        Route::delete('/{pengguna}', [AnggotaController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('keuangan')->name('keuangan.')->group(function () {
        Route::get('/', [KeuanganController::class, 'index'])->name('index');
        Route::get('/histori', [KeuanganController::class, 'histori'])->name('histori');
        Route::get('/donatur/tambah', [KeuanganController::class, 'donaturCreate'])->name('donatur.create');
        Route::post('/donatur', [KeuanganController::class, 'donaturStore'])->name('donatur.store');
        Route::get('/donatur/{donatur}/edit', [KeuanganController::class, 'donaturEdit'])->name('donatur.edit');
        Route::put('/donatur/{donatur}', [KeuanganController::class, 'donaturUpdate'])->name('donatur.update');
    });
    Route::prefix('kas')->name('kas.')->group(function () {
        Route::get('/', [KasBulananController::class, 'index'])->name('index');
        Route::post('/tagihan', [KasBulananController::class, 'generate'])->name('generate');
        Route::post('/regulasi', [KasBulananController::class, 'regulasi'])->name('regulasi');
        Route::post('/tagihan/{tagihan}/bayar', [KasBulananController::class, 'bayar'])->name('bayar');
    });
    Route::prefix('pengajuan')->name('pengajuan.')->group(function () {
        Route::get('/', [PengajuanController::class, 'index'])->name('index');
        Route::post('/', [PengajuanController::class, 'store'])->name('store');
        Route::post('/{pengajuan}/setujui', [PengajuanController::class, 'setujui'])->name('setujui');
        Route::post('/{pengajuan}/tolak', [PengajuanController::class, 'tolak'])->name('tolak');
    });
    Route::post('/logout', function (Request $request) { Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken(); return redirect()->route('login'); })->name('logout');
});
