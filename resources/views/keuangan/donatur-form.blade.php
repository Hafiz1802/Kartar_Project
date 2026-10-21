@extends('layouts.app')
@php $edit = $donatur->exists; @endphp
@section('title', $edit ? 'Ubah Transaksi Donatur' : 'Catat Donatur')
@section('content')
@include('keuangan.umum')
<header class="member-page-head">
    <div>
        <h1>{{ $edit ? 'Ubah Transaksi Donatur' : 'Catat Donatur' }}</h1>
        <p class="sub">Dana donatur dipisahkan dari kas internal.</p>
    </div>
</header>

<section class="card member-form-card">
<form method="POST" enctype="multipart/form-data"
      action="{{ $edit ? route('keuangan.donatur.update', $donatur) : route('keuangan.donatur.store') }}">
    @csrf
    @if($edit) @method('PUT') @endif

    <div class="form-grid">
        <label class="form-field"><span>Tanggal <b>*</b></span>
            <input type="date" name="tanggal" required value="{{ old('tanggal', $donatur->tanggal?->format('Y-m-d')) }}">
            @error('tanggal')<small class="error-text">{{ $message }}</small>@enderror</label>

        <label class="form-field"><span>Jenis <b>*</b></span>
            <select name="status" required>
                <option value="masuk" @selected(old('status', $donatur->status) === 'masuk')>Dana masuk</option>
                <option value="keluar" @selected(old('status', $donatur->status) === 'keluar')>Dana keluar</option>
            </select>
            @error('status')<small class="error-text">{{ $message }}</small>@enderror</label>

        <label class="form-field"><span>Nama donatur</span>
            <input type="text" name="nama_donatur" maxlength="100" value="{{ old('nama_donatur', $donatur->nama_donatur) }}" placeholder="Boleh dikosongkan (anonim)">
            @error('nama_donatur')<small class="error-text">{{ $message }}</small>@enderror</label>

        <label class="form-field"><span>Nominal (Rp) <b>*</b></span>
            <input type="number" name="nominal" min="1" step="1" required value="{{ old('nominal', $donatur->nominal ? (int) $donatur->nominal : '') }}">
            @error('nominal')<small class="error-text">{{ $message }}</small>@enderror</label>

        <label class="form-field full"><span>Keterangan <b>*</b></span>
            <input type="text" name="keterangan" maxlength="255" required value="{{ old('keterangan', $donatur->keterangan) }}">
            @error('keterangan')<small class="error-text">{{ $message }}</small>@enderror</label>

        <label class="form-field full"><span>Bukti foto</span>
            <input type="file" name="bukti_foto" accept="image/*">
            <small class="field-hint">Opsional, maks. 2 MB.@if($edit && $donatur->bukti_foto) Mengunggah file baru menggantikan bukti lama.@endif</small>
            @error('bukti_foto')<small class="error-text">{{ $message }}</small>@enderror</label>
    </div>

    <div class="form-actions">
        <a class="btn-batal" href="{{ route('keuangan.index', ['tab' => 'donatur']) }}">Batal</a>
        <button class="btn-simpan" type="submit">Simpan</button>
    </div>
</form>
</section>
@endsection
