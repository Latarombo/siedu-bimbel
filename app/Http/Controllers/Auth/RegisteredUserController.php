<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SiswaProfile;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Ketentuan Penggunaan #3: registrasi publik cuma boleh bikin akun
     * role 'siswa'. Akun 'admin' lewat Seeder, akun 'guru' dibuat Admin
     * lewat menu kelola data guru (F8) — bukan dari sini.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            // Validasi Data #1: tanggal lahir wajib diisi saat registrasi,
            // dipakai untuk hitung kategori usia (F5) tiap kali daftar kelas nanti.
            // Batas realistis: maksimal 100 tahun lalu, minimal harus hari ini
            // (tidak boleh tanggal masa depan atau tahun ngawur seperti 20000)
            'tanggal_lahir' => 'required|date|after:'.now()->subYears(100)->format('Y-m-d').'|before_or_equal:today',
        ]);

        $user = User::create([
            'role' => 'siswa',
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // F11: profil siswa (termasuk slot data Wali) dibuat sekaligus,
        // data Wali-nya sendiri diisi belakangan lewat halaman profil.
        SiswaProfile::create([
            'user_id' => $user->id,
            'tanggal_lahir' => $request->tanggal_lahir,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}