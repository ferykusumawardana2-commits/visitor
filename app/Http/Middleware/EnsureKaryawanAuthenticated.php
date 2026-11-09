<?php

namespace App\Http\Middleware;

use App\Models\Penduduk;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class EnsureKaryawanAuthenticated
{
    /**
     * Handle an incoming request for Karyawan (Penduduk) portal routes.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $pendudukId = $request->session()->get('penduduk_id');
        $status = $request->session()->get('penduduk_status');

        if ($pendudukId === null || ! in_array($status, ['Penduduk', 'Admin'], true)) {
            return redirect()->route('karyawan.login')
                ->with('error', 'Silakan login sebagai Karyawan terlebih dahulu.');
        }

        $karyawan = Penduduk::with('jabatanPenduduk.jabatan')
            ->where('id_penduduk', $pendudukId)
            ->first();

        if ($karyawan === null) {
            $request->session()->forget(['penduduk_id', 'penduduk_nama', 'penduduk_status']);

            return redirect()->route('karyawan.login')
                ->with('error', 'Sesi Karyawan tidak valid. Silakan login kembali.');
        }

        $request->session()->put('penduduk_nama', $karyawan->nama_penduduk);
        $request->attributes->set('currentKaryawan', $karyawan);
        View::share('currentKaryawan', $karyawan);

        return $next($request);
    }
}
