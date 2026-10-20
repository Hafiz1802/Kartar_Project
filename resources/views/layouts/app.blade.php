<!doctype html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>
            @yield('title', 'Kartar') | Transparansi Keuangan
        </title>
            <style>
:root{--ground:#F6F7F3;--ink:#1B2B26;--muted:#4A5E55;--line:#DDE5DF;--card:#fff;--deep:#16372D;--green:#1F7A5C;--red:#A8401F}*{box-sizing:border-box}body{margin:0;background:var(--ground);color:var(--ink);font-family:system-ui,-apple-system,"Segoe UI",sans-serif;line-height:1.5}button{font:inherit}a{color:inherit;text-decoration:none}.shell{min-height:100vh;display:flex}.sidebar{width:250px;flex-shrink:0;background:var(--deep);padding:25px 16px;display:flex;flex-direction:column;gap:7px;color:#D8E6DF}.brand{font-size:23px;font-weight:850;color:#fff;padding:0 14px 22px}.navlink{display:block;padding:12px 14px;border-radius:9px;color:#D8E6DF;font-weight:650}.navlink.active,.navlink:hover{background:var(--green);color:white}.who{margin-top:auto;padding:18px 14px 8px;font-size:13px;color:#BFD3CA}.who strong{display:block;color:#fff;font-size:15px}.main{flex:1;min-width:0;padding:32px;display:flex;flex-direction:column;gap:24px}.head{display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap}.head h1{font-size:30px;line-height:1.2;margin:0;font-weight:850}.sub{font-size:14px;color:var(--muted);margin:5px 0 0}.cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:16px}.card{background:var(--card);border:1px solid var(--line);border-radius:12px;padding:22px;min-width:0}.stat-label{font-size:14px;color:var(--muted);font-weight:650}.stat-value{font-size:25px;font-weight:850;margin-top:6px}.stat-note{font-size:13px;color:var(--muted);margin-top:5px}.row{display:grid;grid-template-columns:1.25fr 1fr;gap:16px}.card h2{font-size:18px;margin:0 0 16px}.tablewrap{overflow-x:auto}table{width:100%;border-collapse:collapse;min-width:430px;font-size:14px}th{text-align:left;color:var(--muted);font-weight:650;padding:9px 8px 9px 0}td{padding:12px 8px 12px 0;border-top:1px solid #E5ECE7}.right{text-align:right}.amount-in{color:var(--green);font-weight:750}.amount-out{color:var(--red);font-weight:750}.pill{display:inline-block;padding:4px 9px;border-radius:999px;font-size:12px;font-weight:750;background:#D5EBE1;color:#0F4D39}.pill.wait{background:#FBEBC8;color:#6B4A00}.muted{color:var(--muted)}.empty{padding:22px 0;text-align:center;color:var(--muted)}.logout{margin:0 14px 5px;background:none;border:0;text-align:left;color:#D8E6DF;padding:12px 0;cursor:pointer;font-weight:650}@media(max-width:800px){.shell{display:block}.sidebar{width:100%;padding:16px;gap:4px}.brand{padding:0 10px 10px}.navs{display:flex;flex-wrap:wrap}.navlink{padding:9px 11px}.who{margin-top:8px;padding:8px 10px}.logout{padding:8px 10px}.main{padding:22px 16px}.row{grid-template-columns:1fr}}

/* =========================================================
   ANGGOTA - INDEX, CREATE & EDIT
   ========================================================= */

.member-form-card {
    width: 100%;
    padding: 32px;
    background: #FFFFFF;
    border: 1px solid var(--line);
    border-radius: 16px;
    box-shadow: 0 8px 24px rgb(22 55 45 / 5%);
}

/* Header halaman */
.member-page-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
}

.member-page-head h1 {
    margin: 0;
    font-size: 30px;
    line-height: 1.2;
    font-weight: 850;
    letter-spacing: -0.5px;
}

.member-page-head .sub {
    margin-top: 7px;
}

/* =========================================================
   FORM
   ========================================================= */

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    column-gap: 24px;
    row-gap: 22px;
    align-items: start;
}

.form-field {
    display: flex;
    flex-direction: column;
    gap: 8px;
    width: 100%;
    min-width: 0;
    margin: 0;
    padding: 0;
    color: var(--ink);
    font-size: 14px;
}

.form-field > span,
.form-field > label {
    display: block;
    margin: 0;
    color: #243D34;
    font-size: 14px;
    font-weight: 750;
}

.form-field b {
    color: #C93636;
}

/* Input */
.form-field input,
.form-field textarea,
.form-field select {
    width: 100%;
    min-width: 0;
    min-height: 48px;
    padding: 12px 14px;
    border: 1px solid #D6E0DA;
    border-radius: 10px;
    background: #FFFFFF;
    color: var(--ink);
    font-family: inherit;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.5;
    outline: none;
    transition:
        border-color .2s ease,
        box-shadow .2s ease,
        background-color .2s ease;
}

.form-field textarea {
    min-height: 120px;
    resize: vertical;
}

.form-field select {
    cursor: pointer;
}

.form-field input:hover,
.form-field textarea:hover,
.form-field select:hover {
    border-color: #BFCFC7;
}

.form-field input:focus,
.form-field textarea:focus,
.form-field select:focus {
    border-color: var(--green);
    box-shadow: 0 0 0 4px rgb(31 122 92 / 10%);
}

.form-field input::placeholder,
.form-field textarea::placeholder {
    color: #8B9992;
    opacity: 1;
}

/* Role full width */
.form-grid .role-field {
    grid-column: 1 / -1;
}

/* Alamat full width */
.form-grid .full {
    grid-column: 1 / -1;
}

/* Password + konfirmasi otomatis sejajar */
.form-grid .password-field {
    grid-column: span 1;
}

/* Field bantuan */
.field-hint {
    display: block;
    margin: 0;
    color: #718078;
    font-size: 12px;
    font-weight: 400;
    line-height: 1.5;
}

/* Role readonly */
.readonly-field {
    background: #F1F5F2 !important;
    color: #52645A !important;
    border-style: dashed !important;
    cursor: not-allowed;
}

/* Error */
.error-text {
    display: block;
    margin: 0;
    color: #B42332;
    font-size: 12px;
    font-weight: 500;
}

.form-error {
    margin: 0 0 24px;
    padding: 14px 16px;
    border: 1px solid #F1C8CD;
    border-radius: 10px;
    background: #FFF4F5;
    color: #8E2632;
}

.form-error strong {
    font-weight: 750;
}

.form-error ul {
    margin: 7px 0 0;
    padding-left: 20px;
    font-weight: 400;
}

/* =========================================================
   FORM ACTION
   ========================================================= */

.form-actions {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 12px;
    margin-top: 30px;
    padding-top: 24px;
    border-top: 1px solid var(--line);
}

.btn-simpan,
.btn-batal {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    min-height: 46px;
    padding: 11px 20px;
    border: 1px solid transparent;
    border-radius: 10px;
    font-family: inherit;
    font-size: 14px;
    font-weight: 750;
    line-height: 1.5;
    text-decoration: none;
    cursor: pointer;
    transition:
        background-color .2s ease,
        transform .15s ease,
        box-shadow .2s ease;
}

.btn-simpan {
    background: var(--green);
    color: #FFFFFF;
    box-shadow: 0 4px 10px rgb(31 122 92 / 15%);
}

.btn-simpan:hover {
    background: #176348;
    color: #FFFFFF;
    transform: translateY(-1px);
}

.btn-batal {
    background: #FCE9E9;
    color: #A52835;
}

.btn-batal:hover {
    background: #F7D6D9;
    color: #841D29;
}

/* =========================================================
   INDEX ANGGOTA
   ========================================================= */

.member-list-card {
    padding: 0;
    overflow: hidden;
}

.member-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    padding: 20px 22px;
    border-bottom: 1px solid var(--line);
    background: #FFFFFF;
}

.search {
    display: flex;
    align-items: center;
    gap: 9px;
    width: 100%;
}

.search input {
    width: min(440px, 100%);
    min-height: 42px;
    padding: 10px 13px;
    border: 1px solid #D6E0DA;
    border-radius: 9px;
    background: #FFFFFF;
    color: var(--ink);
    font: inherit;
    outline: none;
    transition: .2s ease;
}

.search input:focus {
    border-color: var(--green);
    box-shadow: 0 0 0 3px rgb(31 122 92 / 10%);
}

.search input::placeholder {
    color: #89968F;
}

.action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 42px;
    padding: 9px 15px;
    border-radius: 9px;
    border: 1px solid transparent;
    background: var(--green);
    color: #FFFFFF;
    font-size: 14px;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
    transition: .2s ease;
}

.action:hover {
    background: #176348;
    color: #FFFFFF;
    transform: translateY(-1px);
}

.action.secondary {
    background: #EDF3EF;
    color: #245443;
    border-color: #D7E2DC;
}

.action.secondary:hover {
    background: #DFEBE5;
    color: #1B4638;
}

.action.small {
    min-height: 34px;
    padding: 6px 11px;
    font-size: 13px;
    border-radius: 7px;
}

.action.danger {
    background: #FFF0F0;
    color: #B42332;
    border-color: #F2D2D5;
}

.action.danger:hover {
    background: #FBE1E3;
    color: #941E2A;
}

/* Clear search */
.clear {
    color: var(--muted);
    font-size: 13px;
    font-weight: 650;
}

.clear:hover {
    color: var(--green);
}

/* Table */
.member-list-card .tablewrap {
    overflow-x: auto;
}

.member-list-card table {
    width: 100%;
    min-width: 850px;
    border-collapse: collapse;
    font-size: 14px;
}

.member-list-card th {
    padding: 14px 16px;
    background: #F7F9F7;
    border-bottom: 1px solid var(--line);
    color: #52645A;
    font-size: 12px;
    font-weight: 750;
    text-transform: uppercase;
    letter-spacing: .35px;
    white-space: nowrap;
}

.member-list-card td {
    padding: 16px;
    border-top: 1px solid #E8EEE9;
    vertical-align: middle;
}

.member-list-card tbody tr {
    transition: background-color .15s ease;
}

.member-list-card tbody tr:hover {
    background: #FAFCFB;
}

.member-list-card td strong {
    color: #1D332B;
    font-weight: 750;
}

/* Role badge */
.role-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 5px 10px;
    border-radius: 999px;
    background: #E3F1EB;
    color: #176348;
    font-size: 12px;
    font-weight: 750;
}

.role-badge.role-ketua {
    background: #E8EEF8;
    color: #345B91;
}

.role-badge.role-bendahara {
    background: #FFF1D7;
    color: #805B12;
}

.actions {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 7px;
}

.actions form {
    margin: 0;
}

.empty {
    padding: 45px 20px !important;
    text-align: center;
    color: var(--muted);
}

/* Pagination */
.pagination {
    padding: 18px 22px;
    border-top: 1px solid var(--line);
}

/* Alert */
.notice {
    padding: 13px 16px;
    border-radius: 10px;
    font-size: 14px;
}

.notice.success {
    background: #E8F5EF;
    border: 1px solid #C8E6D8;
    color: #176348;
}

.notice.error {
    background: #FFF1F2;
    border: 1px solid #F1CDD1;
    color: #A52835;
}

/* =========================================================
   RESPONSIVE
   ========================================================= */

@media (max-width: 800px) {
    .member-form-card {
        padding: 22px;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-grid .role-field,
    .form-grid .full,
    .form-grid .password-field {
        grid-column: auto;
    }

    .member-toolbar {
        align-items: stretch;
        flex-direction: column;
    }

    .search {
        flex-wrap: wrap;
    }

    .search input {
        width: 100%;
    }

    .member-page-head {
        flex-direction: column;
    }
}

@media (max-width: 600px) {
    .member-form-card {
        padding: 18px;
        border-radius: 12px;
    }

    .form-grid {
        row-gap: 18px;
    }

    .form-actions {
        flex-direction: column-reverse;
    }

    .btn-simpan,
    .btn-batal {
        width: 100%;
    }

    .action {
        width: 100%;
    }
}

</style>

@stack('styles')
@stack('scripts')

</head>
<body>
@auth
        @php
            $user = auth()->user();
        @endphp

        <div class="shell">
            <aside class="sidebar">
                <div class="brand">Kartar</div>

                <nav class="navs">
                    <a class="navlink {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                       href="{{ route('dashboard') }}">
                        Dashboard
                    </a>

                    @if($user->bolehKelolaKeuangan())
                        <a class="navlink"
                           href="{{ route('dashboard') }}#keuangan">
                            Keuangan
                        </a>

                        <a class="navlink"
                           href="{{ route('dashboard') }}#histori">
                            Histori &amp; Cetak
                        </a>

                        <a class="navlink"
                           href="{{ route('dashboard') }}#kas">
                            Kas Bulanan
                        </a>
                    @elseif($user->isAnggota())
                        <a class="navlink"
                           href="{{ route('dashboard') }}#kas-saya">
                            Kas Bulanan
                        </a>
                    @endif

                    @if($user->isKetua())
                        <a class="navlink {{ request()->routeIs('ketua.anggota.*') ? 'active' : '' }}"
                           href="{{ route('ketua.anggota.index') }}">
                            Kelola Anggota
                        </a>
                    @endif
                </nav>

                <div class="who">
                    Masuk sebagai
                    <strong>{{ $user->nama }}</strong>
                    {{ ucfirst($user->role) }}
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="logout" type="submit">Keluar</button>
                </form>
            </aside>

            <main class="main">
                @yield('content')
            </main>
        </div>
    @else
        @yield('content')
    @endauth

    
</body>
</html>
