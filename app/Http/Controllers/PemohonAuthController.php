<?php

namespace App\Http\Controllers;

use App\Models\Pemohon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use RuntimeException;

class PemohonAuthController extends Controller
{
    /**
     * Show the Pemohon registration form (Daftar - Create).
     */
    public function showRegister(): View
    {
        return view('pemohon.register');
    }

    /**
     * Store a newly registered Pemohon (Daftar - Create).
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'telepon' => ['required', 'string', 'max:15'],
            'email' => ['required', 'email', 'max:255', 'unique:pemohon,email'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
        ]);

        $pemohon = Pemohon::create([
            'nama' => $validated['nama'],
            'telepon' => $validated['telepon'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $request->session()->put('pemohon_id', $pemohon->id_pemohon);
        $request->session()->put('pemohon_nama', $pemohon->nama);

        return redirect()->route('pemohon.kunjungan.index')
            ->with('success', 'Pendaftaran berhasil! Selamat datang di Portal Pemohon Si Kunjung Pemda.');
    }

    /**
     * Show the Pemohon login form (Login - Read).
     */
    public function showLogin(): View
    {
        return view('pemohon.login');
    }

    /**
     * Authenticate a Pemohon account (Login - Read).
     */
    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $pemohon = Pemohon::where('email', $validated['email'])
            ->orWhere('telepon', $validated['email'])
            ->first();

        if ($pemohon === null || ! $this->verifyPassword($validated['password'], $pemohon->password)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email/Telepon atau password Pemohon tidak sesuai.']);
        }

        $request->session()->put('pemohon_id', $pemohon->id_pemohon);
        $request->session()->put('pemohon_nama', $pemohon->nama);

        return redirect()->route('pemohon.kunjungan.index')
            ->with('success', 'Login berhasil! Selamat datang kembali, '.$pemohon->nama.'.');
    }

    /**
     * Log out the currently authenticated Pemohon.
     */
    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget(['pemohon_id', 'pemohon_nama']);

        return redirect()->route('pemohon.login')
            ->with('success', 'Anda telah keluar dari Portal Pemohon.');
    }

    /**
     * Verify password supporting bcrypt/argon hashes as well as plain text or MD5 from SQL imports.
     */
    private function verifyPassword(string $plain, string $stored): bool
    {
        if ($plain === $stored || md5($plain) === $stored) {
            return true;
        }

        try {
            return Hash::check($plain, $stored);
        } catch (RuntimeException) {
            return false;
        }
    }
}
