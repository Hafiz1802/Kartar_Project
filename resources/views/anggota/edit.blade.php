@extends('layouts.app')

@section('title', 'Edit Anggota')

@section('content')

<header class="member-page-head">
    <div>
        <h1>Edit Anggota</h1>

        <p class="sub">
            Perbarui informasi {{ $pengguna->nama }}.
        </p>
    </div>
</header>

<section class="card member-form-card">

    <form
        method="POST"
        action="{{ route('ketua.anggota.update', $pengguna) }}"
    >
        @csrf
        @method('PUT')

        @include('anggota.form', [
            'isEdit' => true,
            'submitLabel' => 'Simpan Perubahan'
        ])
    </form>

</section>

@endsection