<!doctype html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Masuk | Kartar</title>
        <style>
:root{--ground:#F6F7F3;--ink:#1B2B26;--muted:#4A5E55;--deep:#16372D;--green:#1F7A5C;--red:#A8401F}*{box-sizing:border-box}body{margin:0;background:var(--ground);color:var(--ink);font-family:system-ui,-apple-system,"Segoe UI",sans-serif;line-height:1.5}input,button{font:inherit}.login{min-height:100vh;display:flex;flex-wrap:wrap}.hero{flex:1 1 420px;background:var(--deep);color:white;padding:56px;display:flex;flex-direction:column;justify-content:space-between;gap:38px}.brand{font-weight:850;font-size:23px}.hero h1{font-size:clamp(30px,4vw,44px);line-height:1.1;margin:0;font-weight:850;max-width:520px}.hero p{color:#D8E6DF;font-size:18px;max-width:460px;margin:16px 0 0}.side{flex:1 1 420px;display:flex;align-items:center;justify-content:center;padding:40px 24px}.loginbox{width:100%;max-width:380px;display:flex;flex-direction:column;gap:18px}.loginbox h2{font-size:28px;margin:0}.field{display:flex;flex-direction:column;gap:6px}.field label{font-size:14px;font-weight:700}.field input{min-height:46px;padding:0 14px;border:1px solid #8FA399;border-radius:8px;background:white;color:var(--ink)}.btn{min-height:48px;border:0;border-radius:8px;background:var(--green);color:white;font-weight:750;cursor:pointer}.btn:hover{background:#18664c}.note{font-size:13px;color:var(--muted)}.alert{padding:12px 14px;border:1px solid #f2c8ba;background:#fff2ed;border-radius:8px;color:var(--red);font-size:14px}@media(max-width:640px){.hero{padding:32px 24px;min-height:290px}.side{padding:32px 20px}}
</style>
</head>
<body>
    <section class="login">
        <div class="hero">
            <div class="brand">Kartar</div>
            <div>
                <h1>Kas organisasi yang transparan.</h1>
                <p>Pantau uang masuk, uang keluar, dan kas bulanan anggota dalam satu tempat.</p>
            </div>
            <div class="note" style="color:#BFD3CA">Akun anggota dibuat oleh Ketua Kartar.</div>
        </div>
        <div class="side">
            <form class="loginbox" method="POST" action="{{ route('login.process') }}">
                @csrf
                <h2>Masuk</h2>
                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
                </div>
                <div class="field">
                    <label for="password">Kata sandi</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required>
                </div
                >@if($errors->any())
                <div class="alert" role="alert">{{ $errors->first() }}</div>
                @endif
                <button class="btn" type="submit">Masuk</button>
                <p class="note">Gunakan email dan kata sandi yang telah didaftarkan oleh Ketua Kartar. Tidak ada pendaftaran mandiri pada halaman ini.</p>
            </form>
        </div>
    </section>
</body>
</html>
