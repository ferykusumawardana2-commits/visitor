<?php

namespace App\Http\Middleware;

use App\Models\Pemohon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class EnsurePemohonAuthenticated
{
    /**
     * Handle an incoming request for Pemohon portal routes.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $pemohonId = $request->session()->get('pemohon_id');

        if ($pemohonId === null) {
            return redirect()->route('pemohon.login')
                ->with('error', 'Silakan login sebagai Pemohon terlebih dahulu.');
        }

        $pemohon = Pemohon::find($pemohonId);

        if ($pemohon === null) {
            $request->session()->forget(['pemohon_id', 'pemohon_nama']);

            return redirect()->route('pemohon.login')
                ->with('error', 'Sesi Pemohon tidak valid. Silakan login kembali.');
        }

        $request->attributes->set('currentPemohon', $pemohon);
        View::share('currentPemohon', $pemohon);

        return $next($request);
    }
}
