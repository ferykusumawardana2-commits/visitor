<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\JabatanPenduduk;
use App\Models\Kunjungan;
use App\Models\Penduduk;
use App\Models\SettingsKegiatan;
use App\Models\SettingsKunjungan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LandingController extends Controller
{
    /**
     * Display the PT ABCD landing page home.
     */
    public function home(): View
    {
        $jadwalAktif = SettingsKunjungan::with(['jabatanPenduduk.penduduk', 'jabatanPenduduk.jabatan'])
            ->where('status', 'Y')
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam_aktif', 'asc')
            ->limit(6)
            ->get();

        $kegiatanList = SettingsKegiatan::with(['jabatanPenduduk.penduduk', 'jabatanPenduduk.jabatan'])
            ->orderBy('tanggal_awal', 'desc')
            ->limit(4)
            ->get();

        $strukturList = JabatanPenduduk::with(['penduduk', 'jabatan'])
            ->where('status', 'Y')
            ->limit(8)
            ->get();

        $stats = [
            'total_jadwal_aktif' => SettingsKunjungan::where('status', 'Y')->count(),
            'total_karyawan' => Penduduk::where('status', 'Penduduk')->count(),
            'total_jabatan' => Jabatan::count(),
            'total_kunjungan' => Kunjungan::count(),
        ];

        return view('landing.home', compact('jadwalAktif', 'kegiatanList', 'strukturList', 'stats'));
    }

    /**
     * Display the public Jadwal Kunjungan page.
     */
    public function jadwal(Request $request): View
    {
        $query = SettingsKunjungan::with(['jabatanPenduduk.penduduk', 'jabatanPenduduk.jabatan']);

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', (string) $request->input('tanggal'));
        }

        if ($request->filled('status') && in_array(strtoupper((string) $request->input('status')), ['Y', 'T'], true)) {
            $query->where('status', strtoupper((string) $request->input('status')));
        }

        if ($request->filled('id_jabatan')) {
            $idJabatan = (int) $request->input('id_jabatan');
            $query->whereHas('jabatanPenduduk', function ($q) use ($idJabatan): void {
                $q->where('id_jabatan', $idJabatan);
            });
        }

        $jadwalList = $query->orderBy('tanggal', 'asc')
            ->orderBy('jam_aktif', 'asc')
            ->get();

        $kegiatanList = SettingsKegiatan::with(['jabatanPenduduk.penduduk', 'jabatanPenduduk.jabatan'])
            ->orderBy('tanggal_awal', 'desc')
            ->get();

        $jabatanList = Jabatan::orderBy('nama_jabatan')->get();

        return view('landing.jadwal', compact('jadwalList', 'kegiatanList', 'jabatanList'));
    }

    /**
     * Display the public Struktur Karyawan / Penduduk page.
     */
    public function struktur(Request $request): View
    {
        $jabatanList = Jabatan::with(['jabatanPenduduk.penduduk', 'jabatanPenduduk.settingsKunjungan', 'jabatanPenduduk.settingsKegiatan'])
            ->orderBy('id_jabatan', 'asc')
            ->get();

        $strukturQuery = JabatanPenduduk::with(['penduduk', 'jabatan', 'settingsKunjungan', 'settingsKegiatan']);

        if ($request->filled('id_jabatan')) {
            $strukturQuery->where('id_jabatan', (int) $request->input('id_jabatan'));
        }

        if ($request->filled('q')) {
            $search = (string) $request->input('q');
            $strukturQuery->whereHas('penduduk', function ($q) use ($search): void {
                $q->where('nama_penduduk', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        $strukturList = $strukturQuery->orderBy('status', 'asc')
            ->orderBy('id_jabatan', 'asc')
            ->get();

        return view('landing.struktur', compact('jabatanList', 'strukturList'));
    }
}
