<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class GuruController extends Controller
{
    /**
     * F8: Admin CRUD data guru. Guru adalah User dengan role 'guru',
     * dibuat Admin — bukan lewat form register publik (Ketentuan Penggunaan #3).
     */
    public function index(): View
    {
        $guru = User::where('role', 'guru')->latest()->paginate(10);

        return view('admin.guru.index', compact('guru'));
    }

    public function create(): View
    {
        return view('admin.guru.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'role' => 'guru',
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.guru.index')
            ->with('status', 'Akun guru berhasil dibuat.');
    }

    public function edit(User $guru): View
    {
        abort_unless($guru->role === 'guru', 404);

        return view('admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, User $guru): RedirectResponse
    {
        abort_unless($guru->role === 'guru', 404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$guru->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8',
        ]);

        $guru->name = $validated['name'];
        $guru->email = $validated['email'];
        $guru->phone = $validated['phone'] ?? null;

        if (! empty($validated['password'])) {
            $guru->password = Hash::make($validated['password']);
        }

        $guru->save();

        return redirect()->route('admin.guru.index')
            ->with('status', 'Data guru berhasil diperbarui.');
    }

    public function destroy(User $guru): RedirectResponse
    {
        abort_unless($guru->role === 'guru', 404);

        // Business Rule terkait F10: guru yang masih mengampu kelas tidak boleh dihapus
        if ($guru->kelasDiampu()->exists()) {
            return back()->with('error', 'Tidak bisa dihapus, guru ini masih mengampu kelas.');
        }

        $guru->delete();

        return redirect()->route('admin.guru.index')
            ->with('status', 'Akun guru berhasil dihapus.');
    }
}
