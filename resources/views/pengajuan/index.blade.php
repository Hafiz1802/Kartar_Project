@extends('layouts.app')
@section('title', 'Pengajuan Anggaran')
@section('content')
@include('keuangan.umum')
@php $rp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.'); $user = auth()->user(); @endphp

<header class="head">
    <div>
        <h1>Pengajuan Anggaran</h1>
        <p class="sub">Bendahara mengajukan pengeluaran, Ketua menyetujui atau menolak. Pengajuan yang disetujui
            mengurangi kas internal.</p>
    </div>
    <div class="card" style="padding:12px 18px">
        <div class="stat-label">Saldo kas internal</div>
        <div class="stat-value" style="font-size:20px">{{ $rp($saldo) }}</div>
    </div>
</header>

@if($user->isBendahara())
<section class="card">
    <h2>Ajukan pengeluaran</h2>
    <form class="inline-form" method="POST" enctype="multipart/form-data" action="{{ route('pengajuan.store') }}">
        @csrf
        <label style="flex:2 1 260px">Keperluan<input type="text" name="keperluan" maxlength="255" required
                value="{{ old('keperluan') }}">
        </label>
        <label>Nominal (Rp)
            <input type="number" name="nominal" min="1" required value="{{ old('nominal') }}">
        </label>
        <label>Bukti/RAB
            <input type="file" name="bukti_foto" accept="image/*">
        </label>
        <button class="action" type="submit">Kirim pengajuan</button>
    </form>
</section>
@endif

<section class="card member-list-card">
    <h2>Daftar pengajuan</h2>
    <div class="tablewrap">
        <table class="keu-table" style="min-width:620px">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Keperluan</th>
                    <th class="right">Nominal</th>
                    <th>Status</th>@if($user->isKetua())<th class="right">Keputusan</th>@endif
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuan as $p)
                <tr>
                    <td>{{ $p->tanggal_pengajuan->format('d/m/Y') }}
                        <div class="muted" style="font-size:12px">{{ $p->pengaju?->nama }}</div>
                    </td>
                    <td>{{ $p->keperluan }}@if($p->bukti_foto) ·
                        <a href="{{ asset('storage/'.$p->bukti_foto) }}" target="_blank" rel="noopener">bukti</a>
                        @endif
                        @if($p->catatan_ketua)
                        <div class="muted" style="font-size:12px">Catatan Ketua: {{ $p->catatan_ketua }}</div>
                        @endif
                    </td>
                    <td class="right">{{ $rp($p->nominal) }}</td>
                    <td>
                        @if($p->status === 'menunggu')<span class="pill wait">Menunggu</span>
                        @elseif($p->status === 'disetujui')<span class="pill">Disetujui</span>
                        @else<span class="pill bad">Ditolak</span>@endif
                    </td>
                    @if($user->isKetua())
                    <td class="right">
                        @if($p->status === 'menunggu')
                        <details>
                            <summary>Putuskan</summary>
                            <form class="inline-form" method="POST" action="{{ route('pengajuan.setujui', $p) }}">
                                @csrf
                                <label>Catatan (opsional)
                                    <input type="text" name="catatan_ketua" maxlength="255">
                                </label>
                                <button class="action small" type="submit">Setujui</button>
                                <button class="action small danger" type="submit"
                                    formaction="{{ route('pengajuan.tolak', $p) }}">Tolak</button>
                            </form>
                        </details>
                        @endif
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ $user->isKetua() ? 5 : 4 }}" class="empty">Belum ada pengajuan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $pengajuan->links() }}</div>
</section>
@endsection