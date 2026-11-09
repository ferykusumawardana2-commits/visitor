<?php

namespace App\Http\Middleware;

use App\Models\Penduduk;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminAuthenticated
{
    /**
     * Handle an incoming request for Admin portal routes.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $pendudukId = $request->session()->get('penduduk_id');
        $status = $request->session()->get('penduduk_status');

        if ($pendudukId === null || $status !== 'Admin') {
            return redirect()->route('admin.login')
                ->with('error', 'Silakan login sebagai Admin terlebih dahulu.');
        }

        $admin = Penduduk::where('id_penduduk', $pendudukId)
            ->where('status', 'Admin')
            ->first();

        if ($admin === null) {
            $request->session()->forget(['penduduk_id', 'penduduk_nama', 'penduduk_status']);

            return redirect()->route('admin.login')
                ->with('error', 'Sesi Admin tidak valid. Silakan login kembali.');
        }

        $request->session()->put('penduduk_nama', $admin->nama_penduduk);
        $request->attributes->set('currentAdmin', $admin);
        View::share('currentAdmin', $admin);

        return $next($request);
    }
}
