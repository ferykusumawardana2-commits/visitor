<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\Pemohon;
use App\Models\SettingsKegiatan;
use App\Models\SettingsKunjungan;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PemohonKunjunganController extends Controller
{
    /**
     * Display the Pemohon's visit requests and form to create/update/delete visits.
     */
    public function index(Request $request): View
    {
        /** @var Pemohon $pemohon */
        $pemohon = $request->attributes->get('currentPemohon') ?? Pemohon::findOrFail($request->session()->get('pemohon_id'));

        $kunjunganList = Kunjungan::with([
            'settingsKunjungan.jabatanPenduduk.penduduk',
            'settingsKunjungan.jabatanPenduduk.jabatan',
        ])
            ->where('id_pemohon', $pemohon->id_pemohon)
            ->orderBy('id_kunjungan', 'desc')
            ->get();

        $jadwalTersedia = SettingsKunjungan::with([
            'jabatanPenduduk.penduduk',
            'jabatanPenduduk.jabatan',
        ])
            ->where('status', 'Y')
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam_aktif', 'asc')
            ->get();

        $kegiatanList = SettingsKegiatan::with([
            'jabatanPenduduk.penduduk',
            'jabatanPenduduk.jabatan',
        ])
            ->orderBy('tanggal_awal', 'desc')
            ->get();

        $selectedJadwalId = $request->query('id_settings_kunjungan');

        return view('pemohon.kunjungan', compact('pemohon', 'kunjunganList', 'jadwalTersedia', 'kegiatanList', 'selectedJadwalId'));
    }

    /**
     * Store a newly created Kunjungan by Pemohon (Create).
     */
    public function store(Request $request): RedirectResponse
    {
        $pemohonId = (int) $request->session()->get('pemohon_id');

        $validated = $request->validate([
            'id_settings_kunjungan' => ['required', 'integer', 'exists:settings_kunjungan,id_settings_kunjungan'],
            'tujuannya' => ['required', 'string', 'max:255'],
            'tanggal_waktu' => ['required', 'date'],
        ]);

        Kunjungan::create([
            'id_settings_kunjungan' => (int) $validated['id_settings_kunjungan'],
            'id_pemohon' => $pemohonId,
            'tujuannya' => $validated['tujuannya'],
            'tanggal_waktu' => Carbon::parse($validated['tanggal_waktu'])->format('Y-m-d H:i:s'),
            'konfrimasi_admin' => null,
            'tanggal_waktu_admin' => null,
            'konfrimasi_penduduk' => null,
            'tanggal_waktu_penduduk' => null,
            'catatan_admin' => null,
            'catatan_penduduk' => null,
        ]);

        return redirect()->route('pemohon.kunjungan.index')
            ->with('success', 'Pengajuan kunjungan berhasil ditambahkan.');
    }

    /**
     * Update an existing Kunjungan by Pemohon (Update).
     */
    public function update(Request $request, int $idKunjungan): RedirectResponse
    {
        $pemohonId = (int) $request->session()->get('pemohon_id');

        $kunjungan = Kunjungan::where('id_kunjungan', $idKunjungan)
            ->where(function ($query) use ($pemohonId): void {
                $query->where('id_pemohon', $pemohonId)
                    ->orWhereNull('id_pemohon');
            })
            ->firstOrFail();

        $validated = $request->validate([
            'id_settings_kunjungan' => ['required', 'integer', 'exists:settings_kunjungan,id_settings_kunjungan'],
            'tujuannya' => ['required', 'string', 'max:255'],
            'tanggal_waktu' => ['required', 'date'],
        ]);

        $kunjungan->update([
            'id_settings_kunjungan' => (int) $validated['id_settings_kunjungan'],
            'id_pemohon' => $pemohonId,
            'tujuannya' => $validated['tujuannya'],
            'tanggal_waktu' => Carbon::parse($validated['tanggal_waktu'])->format('Y-m-d H:i:s'),
        ]);

        return redirect()->route('pemohon.kunjungan.index')
            ->with('success', 'Data pengajuan kunjungan berhasil diperbarui.');
    }

    /**
     * Delete a Kunjungan by Pemohon (Delete).
     */
    public function destroy(Request $request, int $idKunjungan): RedirectResponse
    {
        $pemohonId = (int) $request->session()->get('pemohon_id');

        $kunjungan = Kunjungan::where('id_kunjungan', $idKunjungan)
            ->where(function ($query) use ($pemohonId): void {
                $query->where('id_pemohon', $pemohonId)
                    ->orWhereNull('id_pemohon');
            })
            ->firstOrFail();

        $kunjungan->delete();

        return redirect()->route('pemohon.kunjungan.index')
            ->with('success', 'Pengajuan kunjungan berhasil dihapus.');
    }

    /**
     * Display the Pemohon's Histori Kunjungan (Read).
     */
    public function histori(Request $request): View
    {
        /** @var Pemohon $pemohon */
        $pemohon = $request->attributes->get('currentPemohon') ?? Pemohon::findOrFail($request->session()->get('pemohon_id'));

        $query = Kunjungan::with([
            'settingsKunjungan.jabatanPenduduk.penduduk',
            'settingsKunjungan.jabatanPenduduk.jabatan',
        ])->where('id_pemohon', $pemohon->id_pemohon);

        $filterStatus = (string) $request->query('status', 'semua');

        if ($filterStatus === 'disetujui') {
            $query->where('konfrimasi_admin', 'Y')->where('konfrimasi_penduduk', 'Y');
        } elseif ($filterStatus === 'ditolak') {
            $query->where(function ($q): void {
                $q->where('konfrimasi_admin', 'T')
                    ->orWhere('konfrimasi_penduduk', 'T');
            });
        } elseif ($filterStatus === 'menunggu') {
            $query->where(function ($q): void {
                $q->whereNull('konfrimasi_admin')
                    ->orWhereNull('konfrimasi_penduduk');
            })->where(function ($q): void {
                $q->where('konfrimasi_admin', '!=', 'T')->orWhereNull('konfrimasi_admin');
            })->where(function ($q): void {
                $q->where('konfrimasi_penduduk', '!=', 'T')->orWhereNull('konfrimasi_penduduk');
            });
        }

        $historiList = $query->orderBy('tanggal_waktu', 'desc')
            ->orderBy('id_kunjungan', 'desc')
            ->get();

        return view('pemohon.histori', compact('pemohon', 'historiList', 'filterStatus'));
    }
}
