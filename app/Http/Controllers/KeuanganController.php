<?php

namespace App\Http\Controllers;

use App\Models\Donatur;
use App\Services\LaporanService;
use App\Services\SaldoService;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            abort_unless(auth()->user()?->bolehKelolaKeuangan(), 403, 'Halaman ini hanya untuk Ketua dan Bendahara.');
            return $next($request);
        });
    }

    private function tahun(Request $request): int
    {
        $t = (int) $request->query('tahun', now()->year);
        return in_array($t, LaporanService::daftarTahun(), true) ? $t : (int) now()->year;
    }

    public function index(Request $request)
    {
        $tahun = $this->tahun($request);
        $tab = $request->query('tab') === 'donatur' ? 'donatur' : 'internal';

        $internal = LaporanService::internal($tahun);
        $donatur = LaporanService::donatur($tahun);

        return view('keuangan.index', [
            'tahun' => $tahun,
            'tab' => $tab,
            'daftarTahun' => LaporanService::daftarTahun(),
            'saldoInternal' => SaldoService::saldoInternal(),
            'saldoEksternal' => SaldoService::saldoEksternal(),
            'internal' => $internal,
            'donatur' => $donatur,
            'masuk' => $tab === 'internal' ? $internal->sum('masuk') : (float) $donatur->where('status', 'masuk')->sum('nominal'),
            'keluar' => $tab === 'internal' ? $internal->sum('keluar') : (float) $donatur->where('status', 'keluar')->sum('nominal'),
        ]);
    }

    public function histori(Request $request)
    {
        $tahun = $this->tahun($request);

        return view('keuangan.histori', [
            'tahun' => $tahun,
            'daftarTahun' => LaporanService::daftarTahun(),
            'tahunan' => LaporanService::rekapTahunan(),
            'bulanan' => LaporanService::rekapBulanan($tahun),
            'internal' => LaporanService::internal($tahun),
            'donatur' => LaporanService::donatur($tahun),
        ]);
    }

    /* ---------- Donatur (tambah & ubah; tidak ada hapus agar histori utuh) ---------- */

    public function donaturCreate()
    {
        return view('keuangan.donatur-form', ['donatur' => new Donatur(['status' => 'masuk', 'tanggal' => now()])]);
    }

    public function donaturStore(Request $request)
    {
        $data = $this->validasiDonatur($request);
        if ($galat = $this->cekSaldo($data['status'], (float) $data['nominal'], null)) {
            return back()->withInput()->withErrors(['nominal' => $galat]);
        }

        $data['dicatat_oleh'] = auth()->id();
        if ($request->hasFile('bukti_foto')) {
            $data['bukti_foto'] = $request->file('bukti_foto')->store('bukti/donatur', 'public');
        }
        Donatur::create($data);

        return redirect()->route('keuangan.index', ['tab' => 'donatur'])->with('success', 'Transaksi donatur dicatat.');
    }

    public function donaturEdit(Donatur $donatur)
    {
        return view('keuangan.donatur-form', compact('donatur'));
    }

    public function donaturUpdate(Request $request, Donatur $donatur)
    {
        $data = $this->validasiDonatur($request);
        if ($galat = $this->cekSaldo($data['status'], (float) $data['nominal'], $donatur)) {
            return back()->withInput()->withErrors(['nominal' => $galat]);
        }

        if ($request->hasFile('bukti_foto')) {
            $data['bukti_foto'] = $request->file('bukti_foto')->store('bukti/donatur', 'public');
        }
        $donatur->update($data);

        return redirect()->route('keuangan.index', ['tab' => 'donatur'])->with('success', 'Transaksi donatur diperbarui.');
    }

    private function validasiDonatur(Request $request): array
    {
        return $request->validate([
            'nama_donatur' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:masuk,keluar'],
            'tanggal' => ['required', 'date'],
            'keterangan' => ['required', 'string', 'max:255'],
            'nominal' => ['required', 'numeric', 'min:1', 'max:9999999999999'],
            'bukti_foto' => ['nullable', 'image', 'max:2048'],
        ], [
            'nominal.min' => 'Nominal harus lebih dari 0.',
        ]);
    }

    /** Saldo dana donatur tidak boleh negatif setelah transaksi disimpan. */
    private function cekSaldo(string $status, float $nominal, ?Donatur $lama): ?string
    {
        $saldo = SaldoService::saldoEksternal();
        if ($lama) {
            $saldo -= $lama->status === 'masuk' ? (float) $lama->nominal : -(float) $lama->nominal;
        }
        $saldo += $status === 'masuk' ? $nominal : -$nominal;

        return $saldo < 0 ? 'Saldo dana donatur tidak mencukupi untuk transaksi ini.' : null;
    }
}
