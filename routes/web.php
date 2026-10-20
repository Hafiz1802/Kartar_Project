<?php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AnggotaController;
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
    Route::post('/logout', function (Request $request) { Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken(); return redirect()->route('login'); })->name('logout');
});
