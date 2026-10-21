@extends('layouts.app')
@section('title', 'Keuangan')
@section('content')
@include('keuangan.umum')
@php $rp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.'); @endphp

<header class="head">
    <div>
        <h1>Keuangan</h1>
        <p class="sub">Pantau uang masuk dan keluar. Transaksi tidak dapat dihapus agar histori tetap utuh.</p>
    </div>
    <div class="toolbar">
        <form method="GET" action="{{ route('keuangan.index') }}">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <select class="sel" name="tahun" onchange="this.form.submit()" aria-label="Tahun">
                @foreach($daftarTahun as $t)<option value="{{ $t }}" @selected($t===$tahun)>Tahun {{ $t }}</option>
                @endforeach
            </select>
        </form>
        @if($tab === 'donatur')
        <a class="action" href="{{ route('keuangan.donatur.create') }}">+ Catat Donatur</a>
        @endif
    </div>
</header>

<nav class="tabs">
    <a class="tab {{ $tab === 'internal' ? 'on' : '' }}"
        href="{{ route('keuangan.index', ['tab' => 'internal', 'tahun' => $tahun]) }}">Internal</a>
    <a class="tab {{ $tab === 'donatur' ? 'on' : '' }}"
        href="{{ route('keuangan.index', ['tab' => 'donatur', 'tahun' => $tahun]) }}">Donatur (eksternal)</a>
</nav>

<section class="cards">
    <div class="card">
        <div class="stat-label">Saldo {{ $tab === 'internal' ? 'kas internal' : 'dana donatur' }}</div>
        <div class="stat-value">{{ $rp($tab === 'internal' ? $saldoInternal : $saldoEksternal) }}</div>
        <div class="stat-note">Semua tahun</div>
    </div>
    <div class="card">
        <div class="stat-label">Masuk {{ $tahun }}</div>
        <div class="stat-value amount-in">{{ $rp($masuk) }}</div>
    </div>
    <div class="card">
        <div class="stat-label">Keluar {{ $tahun }}</div>
        <div class="stat-value amount-out">{{ $rp($keluar) }}</div>
    </div>
</section>

<section class="card member-list-card">
    <h2>Histori {{ $tab === 'internal' ? 'internal' : 'donatur' }} {{ $tahun }}</h2>
    <div class="tablewrap">
        @if($tab === 'internal')
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
                @empty
                <tr>
                    <td colspan="4" class="empty">Belum ada transaksi di tahun ini.</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2">Total</td>
                    <td class="right amount-in">{{ $rp($masuk) }}</td>
                    <td class="right amount-out">{{ $rp($keluar) }}</td>
                </tr>
            </tfoot>
        </table>
        <p class="note muted" style="font-size:13px">Kas internal bertambah otomatis dari pembayaran kas anggota (menu
            Kas Bulanan) dan berkurang dari pengajuan anggaran yang disetujui Ketua.</p>
        @else
        <table class="keu-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Donatur</th>
                    <th>Keterangan</th>
                    <th class="right">Masuk</th>
                    <th class="right">Keluar</th>
                    <th class="right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($donatur as $d)
                <tr>
                    <td>{{ $d->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $d->nama_donatur ?: '-' }}</td>
                    <td>{{ $d->keterangan }}@if($d->bukti_foto) · <a href="{{ asset('storage/'.$d->bukti_foto) }}"
                            target="_blank" rel="noopener">bukti</a>
                        @endif
                    </td>
                    <td class="right amount-in">{{ $d->status === 'masuk' ? $rp($d->nominal) : '-' }}</td>
                    <td class="right amount-out">{{ $d->status === 'keluar' ? $rp($d->nominal) : '-' }}</td>
                    <td class="right">
                        <a class="action small secondary" href="{{ route('keuangan.donatur.edit', $d) }}">Ubah</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="empty">Belum ada transaksi donatur di tahun ini.</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Total</td>
                    <td class="right amount-in">{{ $rp($masuk) }}</td>
                    <td class="right amount-out">{{ $rp($keluar) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        @endif
    </div>
</section>
@endsection