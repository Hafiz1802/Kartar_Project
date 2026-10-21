@extends('layouts.app')
@section('title', 'Kas Bulanan')
@section('content')
@include('keuangan.umum')
@php $rp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.'); @endphp

<header class="head">
    <div>
        <h1>Kas Bulanan</h1>
        <p class="sub">Buat tagihan kas anggota, lalu catat pembayaran. Kas yang dibayar otomatis masuk ke saldo internal.</p>
    </div>
    <form method="GET" action="{{ route('kas.index') }}" class="toolbar">
        <input class="sel" type="month" name="bulan" value="{{ $periode->format('Y-m') }}" onchange="this.form.submit()" aria-label="Bulan">
    </form>
</header>

<section class="cards">
    <div class="card"><div class="stat-label">Tagihan {{ $periode->format('m/Y') }}</div><div class="stat-value">{{ $tagihan->count() }}</div></div>
    <div class="card"><div class="stat-label">Sudah lunas</div><div class="stat-value">{{ $lunas }} dari {{ $tagihan->count() }}</div></div>
    <div class="card"><div class="stat-label">Terkumpul</div><div class="stat-value amount-in">{{ $rp($terkumpul) }}</div></div>
</section>

<section class="row">
    <div class="card">
        <h2>Buat tagihan bulan ini</h2>
        <p class="sub" style="margin:0 0 12px">
            @if($regulasi) Nominal berlaku: <strong>{{ $rp($regulasi->nominal) }}</strong> per anggota, jatuh tempo tanggal {{ $regulasi->hari_jatuh_tempo }}.
            @else <span class="amount-out">Belum ada regulasi kas yang berlaku pada bulan ini.</span> @endif
        </p>
        <form method="POST" action="{{ route('kas.generate') }}">
            @csrf
            <input type="hidden" name="bulan" value="{{ $periode->format('Y-m') }}">
            <button class="action" type="submit">Buat tagihan semua anggota</button>
        </form>
        <p class="sub">Hanya anggota yang belum punya tagihan di bulan ini yang dibuatkan.</p>

        <details style="margin-top:16px">
            <summary>Atur nominal kas</summary>
            <form class="inline-form" method="POST" action="{{ route('kas.regulasi') }}">
                @csrf
                <label>Nominal (Rp)<input type="number" name="nominal" min="1" required value="{{ old('nominal', $regulasi ? (int) $regulasi->nominal : 5000) }}"></label>
                <label>Berlaku mulai<input type="date" name="tanggal_berlaku" required value="{{ old('tanggal_berlaku', $periode->format('Y-m-d')) }}"></label>
                <label>Jatuh tempo (tgl)<input type="number" name="hari_jatuh_tempo" min="1" max="28" required value="{{ old('hari_jatuh_tempo', $regulasi->hari_jatuh_tempo ?? 10) }}"></label>
                <button class="action small" type="submit">Simpan</button>
            </form>
            <p class="sub">Tagihan yang sudah dibuat tidak ikut berubah.</p>
        </details>
    </div>

    <div class="card member-list-card" style="overflow:hidden">
        <h2>Tagihan {{ $periode->format('m/Y') }}</h2>
        <div class="tablewrap">
        <table class="keu-table">
            <thead><tr><th>Anggota</th><th class="right">Tagihan</th><th class="right">Dibayar</th><th>Status</th><th class="right">Aksi</th></tr></thead>
            <tbody>
            @forelse($tagihan as $t)
                <tr>
                    <td><strong>{{ $t->pengguna?->nama ?? '-' }}</strong><div class="muted" style="font-size:12px">Jatuh tempo {{ $t->jatuh_tempo->format('d/m/Y') }}</div></td>
                    <td class="right">{{ $rp($t->nominal) }}</td>
                    <td class="right">{{ $rp($t->dibayar) }}</td>
                    <td>
                        @if($t->sisa <= 0)<span class="pill">Lunas</span>
                        @elseif($t->jatuh_tempo->isPast())<span class="pill bad">Terlambat</span>
                        @elseif($t->dibayar > 0)<span class="pill wait">Sebagian</span>
                        @else<span class="pill wait">Belum bayar</span>@endif
                    </td>
                    <td class="right">
                        @if($t->sisa > 0)
                        <details>
                            <summary>Catat bayar</summary>
                            <form class="inline-form" method="POST" enctype="multipart/form-data" action="{{ route('kas.bayar', $t) }}">
                                @csrf
                                <label>Tanggal<input type="date" name="tanggal_bayar" max="{{ now()->format('Y-m-d') }}" required value="{{ now()->format('Y-m-d') }}"></label>
                                <label>Jumlah (Rp)<input type="number" name="jumlah_bayar" min="1" max="{{ (int) $t->sisa }}" required value="{{ (int) $t->sisa }}"></label>
                                <label>Metode<select name="metode_pembayaran"><option value="tunai">Tunai</option><option value="transfer">Transfer</option></select></label>
                                <label>Bukti<input type="file" name="bukti_foto" accept="image/*"></label>
                                <button class="action small" type="submit">Simpan</button>
                            </form>
                        </details>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="empty">Belum ada tagihan di bulan ini.</td></tr>
            @endforelse
            </tbody>
        </table>
        </div>
    </div>
</section>
@endsection
