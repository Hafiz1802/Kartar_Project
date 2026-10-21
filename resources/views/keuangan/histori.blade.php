@extends('layouts.app')
@section('title', 'Histori & Cetak')
@section('content')
@include('keuangan.umum')
@php
$rp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.');
$bulan =
['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
@endphp

<div class="printonly">
    <h2>Laporan Keuangan Kartar RW 022, Tahun {{ $tahun }}</h2>
    <p class="muted">Dicetak {{ now()->format('d/m/Y') }}</p>
</div>

<header class="head noprint">
    <div>
        <h1>Histori &amp; Cetak</h1>
        <p class="sub">Rekap uang masuk dan keluar per tahun dan per bulan.</p>
    </div>
    <div class="toolbar">
        <form method="GET" action="{{ route('keuangan.histori') }}">
            <select class="sel" name="tahun" onchange="this.form.submit()" aria-label="Tahun">
                @foreach($daftarTahun as $t)
                <option value="{{ $t }}" @selected($t===$tahun)>Tahun {{ $t }}</option>
                @endforeach
            </select>
        </form>
        <button class="action" type="button" onclick="window.print()">Cetak laporan</button>
    </div>
</header>

<section class="card member-list-card">
    <h2>Rekap tahunan</h2>
    <div class="tablewrap">
        <table class="keu-table" style="min-width:560px">
            <thead>
                <tr>
                    <th>Tahun</th>
                    <th class="right">Masuk internal</th>
                    <th class="right">Keluar internal</th>
                    <th class="right">Masuk donatur</th>
                    <th class="right">Keluar donatur</th>
                    <th class="right">Selisih</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tahunan as $r)
                <tr>
                    <td><strong>{{ $r['tahun'] }}</strong></td>
                    <td class="right amount-in">{{ $rp($r['internal_masuk']) }}</td>
                    <td class="right amount-out">{{ $rp($r['internal_keluar']) }}</td>
                    <td class="right amount-in">{{ $rp($r['donatur_masuk']) }}</td>
                    <td class="right amount-out">{{ $rp($r['donatur_keluar']) }}</td>
                    <td class="right">
                        <strong>{{ $rp($r['internal_masuk'] + $r['donatur_masuk'] - $r['internal_keluar'] - $r['donatur_keluar']) }}</strong>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

<section class="card member-list-card">
    <h2>Rincian per bulan, {{ $tahun }}</h2>
    <div class="tablewrap">
        <table class="keu-table" style="min-width:420px">
            <thead>
                <tr>
                    <th>Bulan</th>
                    <th class="right">Masuk</th>
                    <th class="right">Keluar</th>
                    <th class="right">Selisih</th>
                </tr>
            </thead>
            <tbody>
                @php $ada = false; @endphp
                @foreach($bulanan as $i => $b)
                @if($b['masuk'] || $b['keluar'])
                @php $ada = true; @endphp
                <tr>
                    <td>{{ $bulan[$i - 1] }}</td>
                    <td class="right amount-in">{{ $rp($b['masuk']) }}</td>
                    <td class="right amount-out">{{ $rp($b['keluar']) }}</td>
                    <td class="right"><strong>{{ $rp($b['masuk'] - $b['keluar']) }}</strong></td>
                </tr>
                @endif
                @endforeach
                @unless($ada)<tr>
                    <td colspan="4" class="empty">Belum ada data.</td>
                </tr>@endunless
            </tbody>
        </table>
    </div>
</section>

<section class="card member-list-card">
    <h2>Semua transaksi internal {{ $tahun }}</h2>
    <div class="tablewrap">
        <table class="keu-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th class="right">Masuk</th>
                    <th class="right">Keluar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($internal as $r)
                <tr>
                    <td>{{ $r['tanggal']->format('d/m/Y') }}</td>
                    <td>{{ $r['keterangan'] }}</td>
                    <td class="right amount-in">{{ $r['masuk'] ? $rp($r['masuk']) : '-' }}</td>
                    <td class="right amount-out">{{ $r['keluar'] ? $rp($r['keluar']) : '-' }}</td>
                </tr>
                @empty<tr>
                    <td colspan="4" class="empty">Belum ada transaksi.</td>
                </tr>@endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">Total</td>
                    <td class="right amount-in">{{ $rp($internal->sum('masuk')) }}</td>
                    <td class="right amount-out">{{ $rp($internal->sum('keluar')) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</section>

<section class="card member-list-card">
    <h2>Semua transaksi donatur {{ $tahun }}</h2>
    <div class="tablewrap">
        <table class="keu-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Donatur</th>
                    <th>Keterangan</th>
                    <th class="right">Masuk</th>
                    <th class="right">Keluar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($donatur as $d)
                <tr>
                    <td>{{ $d->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $d->nama_donatur ?: '-' }}</td>
                    <td>{{ $d->keterangan }}</td>
                    <td class="right amount-in">{{ $d->status === 'masuk' ? $rp($d->nominal) : '-' }}</td>
                    <td class="right amount-out">{{ $d->status === 'keluar' ? $rp($d->nominal) : '-' }}</td>
                </tr>
                @empty<tr>
                    <td colspan="5" class="empty">Belum ada transaksi.</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Total</td>
                    <td class="right amount-in">{{ $rp($donatur->where('status','masuk')->sum('nominal')) }}</td>
                    <td class="right amount-out">{{ $rp($donatur->where('status','keluar')->sum('nominal')) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</section>
@endsection