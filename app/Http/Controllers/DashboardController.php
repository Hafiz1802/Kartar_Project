<?php
namespace App\Http\Controllers;
use App\Models\Donatur;
use App\Models\PembayaranKas;
use App\Models\Pengguna;
use App\Models\PenyimpananKeuanganEksternal;
use App\Models\PenyimpananKeuanganInternal;
use App\Models\TagihanKas;
use Illuminate\Support\Facades\Auth;
class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('dashboard.index', [
            'user' => $user,
            'saldoInternal' => (float) (PenyimpananKeuanganInternal::first()?->total_keuangan_internal ?? 0),
            'saldoEksternal' => (float) (PenyimpananKeuanganEksternal::first()?->total_keuangan ?? 0),
            'jumlahAnggota' => Pengguna::where('role', 'anggota')->count(),
            'kasTerbaru' => PembayaranKas::with(['tagihan.pengguna'])->latest('tanggal_bayar')->take(5)->get(),
            'donaturTerbaru' => Donatur::latest('tanggal')->take(5)->get(),
            'tagihanSaya' => $user->isAnggota() ? TagihanKas::with('pembayaran')->where('id_pengguna', $user->id_pengguna)->latest('periode')->take(5)->get() : collect(),
        ]);
    }
}
