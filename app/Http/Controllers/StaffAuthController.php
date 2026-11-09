<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use RuntimeException;

class StaffAuthController extends Controller
{
    /**
     * Show the Admin login page (Login - Read).
     */
    public function showAdminLogin(): View
    {
        return view('admin.login', ['roleLabel' => 'Admin']);
    }

    /**
     * Authenticate an Admin or Staff account.
     */
    public function authenticateAdmin(Request $request): RedirectResponse
    {
        return $this->authenticatePenduduk($request, 'Admin');
    }

    /**
     * Show the Karyawan (Penduduk) login page.
     */
    public function showKaryawanLogin(): View
    {
        return view('karyawan.login', ['roleLabel' => 'Karyawan']);
    }

    /**
     * Authenticate a Karyawan (Penduduk) account.
     */
    public function authenticateKaryawan(Request $request): RedirectResponse
    {
        return $this->authenticatePenduduk($request, 'Penduduk');
    }

    /**
     * Log out the currently authenticated Admin or Karyawan.
     */
    public function logout(Request $request): RedirectResponse
    {
        $status = $request->session()->get('penduduk_status');
        $request->session()->forget(['penduduk_id', 'penduduk_nama', 'penduduk_status']);

        if ($status === 'Penduduk') {
            return redirect()->route('karyawan.login')
                ->with('success', 'Anda telah keluar dari Portal Karyawan.');
        }

        return redirect()->route('admin.login')
            ->with('success', 'Anda telah keluar dari Portal Admin.');
    }

    /**
     * Perform authentication against the penduduk table.
     */
    private function authenticatePenduduk(Request $request, string $preferredRole): RedirectResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $penduduk = Penduduk::where('username', $validated['username'])
            ->orWhere('nik', $validated['username'])
            ->first();

        if ($penduduk === null || ! $this->verifyPassword($validated['password'], $penduduk->password)) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['username' => 'Username/NIK atau password tidak sesuai.']);
        }

        $request->session()->put('penduduk_id', $penduduk->id_penduduk);
        $request->session()->put('penduduk_nama', $penduduk->nama_penduduk);
        $request->session()->put('penduduk_status', $penduduk->status);

        if ($penduduk->status === 'Admin' && $preferredRole === 'Admin') {
            return redirect()->route('admin.kunjungan.index')
                ->with('success', 'Selamat datang di Panel Admin Si Kunjung Pemda, '.$penduduk->nama_penduduk.'.');
        }

        if ($penduduk->status === 'Penduduk') {
            return redirect()->route('karyawan.kunjungan.index')
                ->with('success', 'Selamat datang di Portal Karyawan Si Kunjung Pemda, '.$penduduk->nama_penduduk.'.');
        }

        return redirect()->route('admin.kunjungan.index')
            ->with('success', 'Selamat datang, '.$penduduk->nama_penduduk.'.');
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
