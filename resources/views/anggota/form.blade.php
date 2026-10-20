@if ($errors->any())
    <div class="notice error form-error">
        <strong>Data belum dapat disimpan.</strong>

        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-grid">

    {{-- Nama --}}
    <label class="form-field">
        <span>Nama lengkap <b>*</b></span>

        <input
            type="text"
            name="nama"
            value="{{ old('nama', $pengguna->nama ?? '') }}"
            maxlength="100"
            placeholder="Masukkan nama lengkap"
            autocomplete="name"
            required
        >

        @error('nama')
            <small class="error-text">{{ $message }}</small>
        @enderror
    </label>


    {{-- Email --}}
    <label class="form-field">
        <span>Email <b>*</b></span>

        <input
            type="email"
            name="email"
            value="{{ old('email', $pengguna->email ?? '') }}"
            maxlength="100"
            placeholder="nama@email.com"
            autocomplete="email"
            required
        >

        @error('email')
            <small class="error-text">{{ $message }}</small>
        @enderror
    </label>


    {{-- Nomor HP --}}
    <label class="form-field">
        <span>Nomor HP <b>*</b></span>

        <input
            type="tel"
            name="no_hp"
            value="{{ old('no_hp', $pengguna->no_hp ?? '') }}"
            maxlength="12"
            placeholder="Contoh: 081234567890"
            autocomplete="tel"
            required
        >

        @error('no_hp')
            <small class="error-text">{{ $message }}</small>
        @enderror
    </label>


    {{-- Alamat --}}
    <label class="form-field full">
        <span>Alamat <b>*</b></span>

        <textarea
            name="alamat"
            rows="4"
            maxlength="255"
            placeholder="Masukkan alamat lengkap"
            autocomplete="street-address"
            required
        >{{ old('alamat', $pengguna->alamat ?? '') }}</textarea>

        @error('alamat')
            <small class="error-text">{{ $message }}</small>
        @enderror
    </label>


    {{-- ROLE FULL WIDTH --}}
    <div class="form-field role-field">

        <label for="role">
            Role <b>*</b>
        </label>

        @if ($isEdit)

            <select name="role" id="role" required>
                <option value="anggota"
                    @selected(old('role', $pengguna->role) === 'anggota')>
                    Anggota
                </option>

                <option value="bendahara"
                    @selected(old('role', $pengguna->role) === 'bendahara')>
                    Bendahara
                </option>

                <option value="ketua"
                    @selected(old('role', $pengguna->role) === 'ketua')>
                    Ketua
                </option>
            </select>

            <small class="field-hint">
                Pilih peran anggota sesuai kebutuhan.
            </small>

        @else

            <input
                type="text"
                id="role"
                value="Anggota"
                readonly
                aria-readonly="true"
                class="readonly-field"
            >

            <small class="field-hint">
                Role anggota ditetapkan otomatis.
            </small>

        @endif

        @error('role')
            <small class="error-text">{{ $message }}</small>
        @enderror

    </div>


    {{-- PASSWORD --}}
    <label class="form-field password-field">

        <span>Kata sandi</span>

        <small class="field-hint">
            @if ($isEdit)
                Kosongkan jika tidak ingin mengubah kata sandi.
            @else
                Minimal 8 karakter.
            @endif
        </small>

        <input
            type="password"
            name="password"
            minlength="8"
            {{ $isEdit ? '' : 'required' }}
            autocomplete="new-password"
            placeholder="{{ $isEdit ? 'Kata sandi baru (opsional)' : 'Buat kata sandi' }}"
        >

        @error('password')
            <small class="error-text">{{ $message }}</small>
        @enderror

    </label>


    {{-- KONFIRMASI PASSWORD --}}
    <label class="form-field password-field">

        <span>Konfirmasi kata sandi</span>

        <small class="field-hint">
            Masukkan ulang kata sandi.
        </small>

        <input
            type="password"
            name="password_confirmation"
            minlength="8"
            {{ $isEdit ? '' : 'required' }}
            autocomplete="new-password"
            placeholder="Ulangi kata sandi"
        >

    </label>

</div>


{{-- ACTION --}}
<div class="form-actions">

    <a
        href="{{ route('ketua.anggota.index') }}"
        class="btn-batal"
    >
        Batal
    </a>

    <button
        type="submit"
        class="btn-simpan"
    >
        {{ $submitLabel ?? 'Simpan' }}
    </button>

</div>