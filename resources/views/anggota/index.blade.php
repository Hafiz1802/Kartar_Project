@extends('layouts.app')

@section('title', 'Kelola Anggota')

@section('content')

<header class="member-page-head">

    <div>
        <h1>Kelola Anggota</h1>

        <p class="sub">
            Tambah, cari, dan perbarui data anggota Karang Taruna.
        </p>
    </div>

    <a
        class="action"
        href="{{ route('ketua.anggota.create') }}"
    >
        + Tambah Anggota
    </a>

</header>


@if(session('success'))
    <div class="notice success">
        {{ session('success') }}
    </div>
@endif


@if(session('error'))
    <div class="notice error">
        {{ session('error') }}
    </div>
@endif


@if($errors->any())
    <div class="notice error">
        {{ $errors->first() }}
    </div>
@endif


<section class="card member-list-card">

    <div class="member-toolbar">

        <form
            class="search"
            method="GET"
            action="{{ route('ketua.anggota.index') }}"
        >

            <input
                type="search"
                name="q"
                value="{{ $kataKunci }}"
                placeholder="Cari nama, email, atau nomor HP"
            >

            <button
                class="action secondary"
                type="submit"
            >
                Cari
            </button>

            @if($kataKunci !== '')
                <a
                    class="clear"
                    href="{{ route('ketua.anggota.index') }}"
                >
                    Reset
                </a>
            @endif

        </form>

    </div>


    <div class="tablewrap">

        <table>

            <thead>
                <tr>
                    <th>Nama Anggota</th>
                    <th>Email</th>
                    <th>Nomor HP</th>
                    <th>Alamat</th>
                    <th>Role</th>
                    <th class="right">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($anggota as $item)

                    <tr>

                        <td>
                            <strong>
                                {{ $item->nama }}
                            </strong>
                        </td>

                        <td>
                            {{ $item->email }}
                        </td>

                        <td>
                            {{ $item->no_hp }}
                        </td>

                        <td>
                            {{ $item->alamat }}
                        </td>

                        <td>
                            <span class="role-badge role-{{ $item->role }}">
                                {{ ucfirst($item->role) }}
                            </span>
                        </td>

                        <td class="right">

                            <div class="actions">

                                <a
                                    class="action small secondary"
                                    href="{{ route('ketua.anggota.edit', $item) }}"
                                >
                                    Edit
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route('ketua.anggota.destroy', $item) }}"
                                    onsubmit="return confirm('Yakin ingin menghapus anggota ini?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="action small danger"
                                        type="submit"
                                    >
                                        Hapus
                                    </button>
                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="empty">
                            {{ $kataKunci
                                ? 'Anggota tidak ditemukan.'
                                : 'Belum ada data anggota.' }}
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    <div class="pagination">
        {{ $anggota->links() }}
    </div>

</section>

@endsection