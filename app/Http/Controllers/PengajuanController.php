<?php

namespace App\Http\Controllers;

use App\Models\PengajuanAnggaran;
use App\Services\SaldoService;
use Illuminate\Http\Request;
use RuntimeException;

class PengajuanController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            abort_unless(auth()->user()?->bolehKelolaKeuangan(), 403, 'Halaman ini hanya untuk Ketua dan Bendahara.');
            return $next($request);
        });
    }

    public function index()
    {
        return view('pengajuan.index', [
            'pengajuan' => PengajuanAnggaran::with(['pengaju', 'pemutus'])->orderByRaw("status = 'menunggu' desc")->latest('tanggal_pengajuan')->latest('id_pengajuan')->paginate(10),
            'saldo' => SaldoService::saldoInternal(),
        ]);
    }

    /** Bendahara mengajukan pengeluaran; Ketua yang memutuskan. */
    public function store(Request $request)
    {
        abort_unless(auth()->user()->isBendahara(), 403, 'Hanya Bendahara yang dapat mengajukan anggaran.');

        $data = $request->validate([
            'keperluan' => ['required', 'string', 'max:255'],
            'nominal' => ['required', 'numeric', 'min:1', 'max:99999999999'],
            'bukti_foto' => ['nullable', 'image', 'max:2048'],
        ]);

        $data['diajukan_oleh'] = auth()->id();
        $data['tanggal_pengajuan'] = now()->toDateString();
        if ($request->hasFile('bukti_foto')) {
            $data['bukti_foto'] = $request->file('bukti_foto')->store('bukti/pengajuan', 'public');
        }
        PengajuanAnggaran::create($data);

        return back()->with('success', 'Pengajuan dikirim ke Ketua.');
    }

    public function setujui(Request $request, PengajuanAnggaran $pengajuan)
    {
        return $this->putuskan($request, $pengajuan, 'setujui', 'Pengajuan disetujui. Saldo kas internal berkurang.');
    }

    public function tolak(Request $request, PengajuanAnggaran $pengajuan)
    {
        return $this->putuskan($request, $pengajuan, 'tolak', 'Pengajuan ditolak.');
    }

    private function putuskan(Request $request, PengajuanAnggaran $pengajuan, string $aksi, string $pesan)
    {
        abort_unless(auth()->user()->isKetua(), 403, 'Hanya Ketua yang dapat memutuskan pengajuan.');
        $catatan = $request->validate(['catatan_ketua' => ['nullable', 'string', 'max:255']])['catatan_ketua'] ?? null;

        try {
            $pengajuan->{$aksi}(auth()->user(), $catatan);
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', $pesan);
    }
}