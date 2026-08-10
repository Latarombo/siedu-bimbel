<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
     * Registrasi publik cuma bikin akun role 'orang_tua'. Data anak
     * TIDAK diisi di sini — itu ditambahkan belakangan lewat menu
     * "Kelola Profil Anak" di dashboard (F11 versi baru).
     */
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            // Dua checkbox consent (gambar 1) — wajib dicentang, tidak bisa lanjut tanpanya
            'consent_privasi' => 'accepted',
            'consent_wali' => 'accepted',
        ]);

        $now = now();

        $user = User::create([
            'role' => 'orang_tua',
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'privasi_disetujui_at' => $now,
            'wali_disetujui_at' => $now,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
