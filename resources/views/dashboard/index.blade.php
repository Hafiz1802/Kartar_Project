
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <header class="head">
        <div>
            <h1>Ringkasan Keuangan</h1>
            <p class="sub">
                Pantau kondisi keuangan Karang Taruna RW 022.
            </p>
        </div>

        <span class="pill">{{ ucfirst($user->role) }}</span>
    </header>

    {{-- Mulai dari sini, pertahankan konten Dashboard lama --}}
    <section class="cards">
        {{-- Kartu saldo kas internal --}}
        {{-- Kartu saldo dana donatur --}}
        {{-- Kartu jumlah anggota khusus Ketua --}}
    </section>

    {{-- Pertahankan seluruh bagian histori kas dan donatur
         beserta kondisi role yang sudah ada. --}}
@endsection
