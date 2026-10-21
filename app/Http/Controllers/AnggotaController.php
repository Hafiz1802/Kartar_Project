<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AnggotaController extends Controller
{
    private function pastikanKetua(): void
    {
        abort_unless(auth()->user()?->isKetua(), 403, 'Halaman ini hanya dapat diakses oleh Ketua.');
    }

    public function index(Request $request)
    {
        $this->pastikanKetua();
        $kataKunci = trim((string) $request->query('q', ''));
        $anggota = Pengguna::query()
            ->when($kataKunci !== '', function ($query) use ($kataKunci) {
                $query->where(function ($subQuery) use ($kataKunci) {
                    $subQuery->where('nama', 'like', "%{$kataKunci}%")
                        ->orWhere('email', 'like', "%{$kataKunci}%")
                        ->orWhere('no_hp', 'like', "%{$kataKunci}%");
                });
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('anggota.index', compact('anggota', 'kataKunci'));
    }

    public function create()
    {
        $this->pastikanKetua();
        return view('anggota.create');
    }

    public function store(Request $request)
    {
        $this->pastikanKetua();
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:pengguna,email'],
            'no_hp' => ['required', 'string', 'max:12'],
            'alamat' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'email.unique' => 'Email sudah digunakan akun lain.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sama.',
        ]);

        $data['role'] = 'anggota';
        Pengguna::create($data);

        return redirect()->route('ketua.anggota.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function edit(Pengguna $pengguna)
    {
        $this->pastikanKetua();
        return view('anggota.edit', compact('pengguna'));
    }

    public function update(Request $request, Pengguna $pengguna)
    {
        $this->pastikanKetua();
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', Rule::unique('pengguna', 'email')->ignore($pengguna->id_pengguna, 'id_pengguna')],
            'no_hp' => ['required', 'string', 'max:12'],
            'alamat' => ['required', 'string', 'max:255'],
            'role' => ['required', Rule::in(['anggota', 'bendahara', 'ketua'])],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'email.unique' => 'Email sudah digunakan akun lain.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sama.',
        ]);

        if (filled($data['password'] ?? null)) {
            $pengguna->password = $data['password'];
        } else {
            unset($data['password']);
        }
        $pengguna->fill($data);
        $pengguna->save();

        return redirect()->route('ketua.anggota.index')->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(Pengguna $pengguna)
    {
        $this->pastikanKetua();
        if ($pengguna->id_pengguna === auth()->id()) {
            return redirect()->route('ketua.anggota.index')->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        if ($pengguna->role === 'ketua') {
            return redirect()->route('ketua.anggota.index')->with('error', 'Akun Ketua tidak dapat dihapus.');
        }

        if ($pengguna->tagihan()->exists()) {
            return redirect()->route('ketua.anggota.index')->with('error', 'Anggota tidak dapat dihapus karena sudah memiliki riwayat tagihan kas.');
        }

        $pengguna->delete();
        return redirect()->route('ketua.anggota.index')->with('success', 'Anggota berhasil dihapus.');
    }
}
