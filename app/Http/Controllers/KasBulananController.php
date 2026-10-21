<?php

namespace App\Http\Controllers;

use App\Models\PembayaranKas;
use App\Models\Pengguna;
use App\Models\RegulasiKas;
use App\Models\TagihanKas;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KasBulananController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            abort_unless(auth()->user()?->bolehKelolaKeuangan(), 403, 'Halaman ini hanya untuk Ketua dan Bendahara.');
            return $next($request);
        });
    }

    private function periode(?string $bulan): Carbon
    {
        return preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', (string) $bulan)
            ? Carbon::createFromFormat('!Y-m', $bulan)
            : now()->startOfMonth();
    }

    public function index(Request $request)
    {
        $periode = $this->periode($request->query('bulan'));

        $tagihan = TagihanKas::with(['pengguna', 'pembayaran'])
            ->whereDate('periode', $periode->toDateString())
            ->get()
            ->sortBy(fn ($t) => strtolower($t->pengguna?->nama ?? ''))
            ->values();

        foreach ($tagihan as $t) {
            $t->dibayar = (float) $t->pembayaran->where('status_bayar', 'lunas')->sum('jumlah_bayar');
            $t->sisa = max(0, (float) $t->nominal - $t->dibayar);
        }

        return view('kas.index', [
            'periode' => $periode,
            'tagihan' => $tagihan,
            'lunas' => $tagihan->where('sisa', 0)->count(),
            'terkumpul' => $tagihan->sum('dibayar'),
            'regulasi' => RegulasiKas::berlakuPada($periode),
        ]);
    }

    /** Buat tagihan bulan itu untuk semua anggota yang belum punya tagihan. */
    public function generate(Request $request)
    {
        $request->validate(['bulan' => ['required', 'regex:/^\d{4}-(0[1-9]|1[0-2])$/']]);
        $periode = $this->periode($request->input('bulan'));

        $regulasi = RegulasiKas::berlakuPada($periode);
        if (! $regulasi) {
            return back()->with('error', 'Belum ada regulasi kas yang berlaku pada bulan itu. Atur nominal kas lebih dulu.');
        }

        $jatuhTempo = $periode->copy()->day(min($regulasi->hari_jatuh_tempo, $periode->daysInMonth));
        $dibuat = 0;

        foreach (Pengguna::where('role', 'anggota')->get() as $anggota) {
            $tagihan = TagihanKas::firstOrCreate(
                ['id_pengguna' => $anggota->id_pengguna, 'periode' => $periode->toDateString()],
                ['id_regulasi' => $regulasi->id_regulasi, 'nominal' => $regulasi->nominal, 'jatuh_tempo' => $jatuhTempo->toDateString()]
            );
            $dibuat += $tagihan->wasRecentlyCreated ? 1 : 0;
        }

        return redirect()->route('kas.index', ['bulan' => $periode->format('Y-m')])
            ->with('success', $dibuat ? "{$dibuat} tagihan dibuat." : 'Semua anggota sudah punya tagihan bulan ini.');
    }

    /** Nominal kas baru berlaku untuk tagihan yang dibuat setelah tanggal berlakunya. */
    public function regulasi(Request $request)
    {
        $data = $request->validate([
            'nominal' => ['required', 'numeric', 'min:1', 'max:99999999999'],
            'tanggal_berlaku' => ['required', 'date'],
            'hari_jatuh_tempo' => ['required', 'integer', 'between:1,28'],
        ]);
        RegulasiKas::create($data);

        return back()->with('success', 'Regulasi kas baru disimpan. Tagihan yang sudah dibuat tidak berubah.');
    }

    public function bayar(Request $request, TagihanKas $tagihan)
    {
        $data = $request->validate([
            'tanggal_bayar' => ['required', 'date', 'before_or_equal:today'],
            'jumlah_bayar' => ['required', 'numeric', 'min:1'],
            'metode_pembayaran' => ['required', 'in:tunai,transfer'],
            'bukti_foto' => ['nullable', 'image', 'max:2048'],
        ], [
            'tanggal_bayar.before_or_equal' => 'Tanggal bayar tidak boleh di masa depan.',
        ]);

        $sisa = (float) $tagihan->nominal - $tagihan->totalDibayar();
        if ($sisa <= 0) {
            return back()->with('error', 'Tagihan ini sudah lunas.');
        }
        if ((float) $data['jumlah_bayar'] > $sisa) {
            return back()->with('error', 'Jumlah melebihi sisa tagihan (Rp ' . number_format($sisa, 0, ',', '.') . ').');
        }

        $data['id_tagihan'] = $tagihan->id_tagihan;
        $data['dicatat_oleh'] = auth()->id();
        $data['status_bayar'] = 'lunas';
        if ($request->hasFile('bukti_foto')) {
            $data['bukti_foto'] = $request->file('bukti_foto')->store('bukti/kas', 'public');
        }
        PembayaranKas::create($data);

        return redirect()->route('kas.index', ['bulan' => $tagihan->periode->format('Y-m')])
            ->with('success', 'Pembayaran dicatat dan masuk ke kas internal.');
    }
}
