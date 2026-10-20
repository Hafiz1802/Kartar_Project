@extends('layouts.app')

@section('title', 'Tambah Anggota')

@section('content')

<header class="member-page-head">
    <div>
        <h1>Tambah Anggota</h1>

        <p class="sub">
            Tambahkan anggota baru Karang Taruna.
            Role otomatis ditetapkan sebagai Anggota.
        </p>
    </div>
</header>

<section class="card member-form-card">

    <form
        method="POST"
        action="{{ route('ketua.anggota.store') }}"
    >
        @csrf

        @include('anggota.form', [
            'isEdit' => false,
            'submitLabel' => 'Simpan Anggota'
        ])
    </form>

</section>

@endsection