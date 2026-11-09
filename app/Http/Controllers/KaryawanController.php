<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\JabatanPenduduk;
use App\Models\Kunjungan;
use App\Models\Penduduk;
use App\Models\SettingsKegiatan;
use App\Models\SettingsKunjungan;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KaryawanController extends Controller
{
    /**
     * Display Kunjungan list for the logged-in Karyawan (Read).
     */
    public function kunjunganIndex(Request $request): View
    {
        $karyawan = $this->getCurrentKaryawan($request);
        $jabatanPendudukIds = $karyawan->jabatanPenduduk()->pluck('id_jabatan_penduduk');

        $kunjunganList = Kunjungan::with([
            'pemohon',
            'settingsKunjungan.jabatanPenduduk.penduduk',
            'settingsKunjungan.jabatanPenduduk.jabatan',
        ])
            ->whereHas('settingsKunjungan', function ($query) use ($jabatanPendudukIds): void {
                $query->whereIn('id_jabatan_penduduk', $jabatanPendudukIds);
            })
            ->orderBy('id_kunjungan', 'desc')
            ->get();

        return view('karyawan.kunjungan', compact('karyawan', 'kunjunganList'));
    }

    /**
     * Update Kunjungan confirmation by Karyawan (status Y/T & catatan ditolak).
     */
    public function kunjunganUpdate(Request $request, int $idKunjungan): RedirectResponse
    {
        $kunjungan = Kunjungan::findOrFail($idKunjungan);

        $statusInput = strtoupper((string) ($request->input('status') ?? $request->input('konfrimasi_penduduk')));
        $request->merge(['status_normalized' => $statusInput]);

        $validated = $request->validate([
            'status_normalized' => ['required', 'in:Y,T'],
            'catatan_penduduk' => [$statusInput === 'T' ? 'required' : 'nullable', 'string', 'max:255'],
        ], [
            'catatan_penduduk.required' => 'Catatan ditolak wajib diisi apabila status kunjungan ditolak (T).',
        ]);

        $kunjungan->update([
            'konfrimasi_penduduk' => $validated['status_normalized'],
            'tanggal_waktu_penduduk' => Carbon::now()->format('Y-m-d H:i:s'),
            'catatan_penduduk' => $validated['status_normalized'] === 'T'
                ? $validated['catatan_penduduk']
                : ($validated['catatan_penduduk'] ?? null),
        ]);

        return redirect()->route('karyawan.kunjungan.index')
            ->with('success', 'Konfirmasi kunjungan oleh Karyawan berhasil diperbarui ('.$validated['status_normalized'].').');
    }

    /**
     * Display Setting Kunjungan for the logged-in Karyawan (Read).
     */
    public function settingsKunjunganIndex(Request $request): View
    {
        $karyawan = $this->getCurrentKaryawan($request);
        $jabatanPendudukIds = $karyawan->jabatanPenduduk()->pluck('id_jabatan_penduduk');

        $settingsList = SettingsKunjungan::with([
            'jabatanPenduduk.penduduk',
            'jabatanPenduduk.jabatan',
        ])
            ->whereIn('id_jabatan_penduduk', $jabatanPendudukIds)
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_aktif', 'asc')
            ->get();

        $jabatanList = Jabatan::orderBy('nama_jabatan', 'asc')->get();

        return view('karyawan.settings_kunjungan', compact('karyawan', 'settingsList', 'jabatanList'));
    }

    /**
     * Store a new Setting Kunjungan for the logged-in Karyawan (Create).
     */
    public function settingsKunjunganStore(Request $request): RedirectResponse
    {
        $karyawan = $this->getCurrentKaryawan($request);

        $statusNormalized = strtoupper((string) $request->input('status', 'Y'));
        $request->merge(['status' => $statusNormalized]);

        $validated = $request->validate([
            'id_jabatan' => ['nullable', 'integer', 'exists:jabatan,id_jabatan'],
            'id_jabatan_penduduk' => ['nullable', 'integer', 'exists:jabatan_penduduk,id_jabatan_penduduk'],
            'tanggal' => ['required', 'date'],
            'jam_aktif' => ['required'],
            'jam_non_aktif' => ['required'],
            'status' => ['required', 'in:Y,T'],
        ]);

        $idJabatanPenduduk = $this->resolveKaryawanJabatanPendudukId($karyawan, $validated);

        SettingsKunjungan::create([
            'id_jabatan_penduduk' => $idJabatanPenduduk,
            'tanggal' => $validated['tanggal'],
            'jam_aktif' => $validated['jam_aktif'],
            'jam_non_aktif' => $validated['jam_non_aktif'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('karyawan.settings-kunjungan.index')
            ->with('success', 'Setting jadwal kunjungan Karyawan berhasil ditambahkan.');
    }

    /**
     * Update an existing Setting Kunjungan for the logged-in Karyawan (Update).
     */
    public function settingsKunjunganUpdate(Request $request, int $idSettingsKunjungan): RedirectResponse
    {
        $karyawan = $this->getCurrentKaryawan($request);
        $setting = SettingsKunjungan::findOrFail($idSettingsKunjungan);

        $statusNormalized = strtoupper((string) $request->input('status', 'Y'));
        $request->merge(['status' => $statusNormalized]);

        $validated = $request->validate([
            'id_jabatan' => ['nullable', 'integer', 'exists:jabatan,id_jabatan'],
            'id_jabatan_penduduk' => ['nullable', 'integer', 'exists:jabatan_penduduk,id_jabatan_penduduk'],
            'tanggal' => ['required', 'date'],
            'jam_aktif' => ['required'],
            'jam_non_aktif' => ['required'],
            'status' => ['required', 'in:Y,T'],
        ]);

        $idJabatanPenduduk = $this->resolveKaryawanJabatanPendudukId($karyawan, $validated);

        $setting->update([
            'id_jabatan_penduduk' => $idJabatanPenduduk,
            'tanggal' => $validated['tanggal'],
            'jam_aktif' => $validated['jam_aktif'],
            'jam_non_aktif' => $validated['jam_non_aktif'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('karyawan.settings-kunjungan.index')
            ->with('success', 'Setting jadwal kunjungan Karyawan berhasil diperbarui.');
    }

    /**
     * Delete a Setting Kunjungan for the logged-in Karyawan (Delete).
     */
    public function settingsKunjunganDestroy(int $idSettingsKunjungan): RedirectResponse
    {
        $setting = SettingsKunjungan::findOrFail($idSettingsKunjungan);
        $setting->delete();

        return redirect()->route('karyawan.settings-kunjungan.index')
            ->with('success', 'Setting jadwal kunjungan Karyawan berhasil dihapus.');
    }

    /**
     * Display Kegiatan for the logged-in Karyawan (Read).
     */
    public function kegiatanIndex(Request $request): View
    {
        $karyawan = $this->getCurrentKaryawan($request);
        $jabatanPendudukIds = $karyawan->jabatanPenduduk()->pluck('id_jabatan_penduduk');

        $kegiatanList = SettingsKegiatan::with([
            'jabatanPenduduk.penduduk',
            'jabatanPenduduk.jabatan',
        ])
            ->whereIn('id_jabatan_penduduk', $jabatanPendudukIds)
            ->orderBy('tanggal_awal', 'desc')
            ->get();

        $jabatanList = Jabatan::orderBy('nama_jabatan', 'asc')->get();

        return view('karyawan.kegiatan', compact('karyawan', 'kegiatanList', 'jabatanList'));
    }

    /**
     * Store a new Kegiatan for the logged-in Karyawan (Create).
     */
    public function kegiatanStore(Request $request): RedirectResponse
    {
        $karyawan = $this->getCurrentKaryawan($request);

        if (! $request->filled('kegiatan') && $request->filled('deskripsi_kegiatan')) {
            $request->merge(['kegiatan' => $request->input('deskripsi_kegiatan')]);
        }

        $validated = $request->validate([
            'id_jabatan' => ['nullable', 'integer', 'exists:jabatan,id_jabatan'],
            'id_jabatan_penduduk' => ['nullable', 'integer', 'exists:jabatan_penduduk,id_jabatan_penduduk'],
            'tanggal_awal' => ['required', 'date'],
            'tanggal_akhir' => ['required', 'date', 'after_or_equal:tanggal_awal'],
            'kegiatan' => ['required', 'string', 'max:255'],
        ]);

        $idJabatanPenduduk = $this->resolveKaryawanJabatanPendudukId($karyawan, $validated);

        SettingsKegiatan::create([
            'id_jabatan_penduduk' => $idJabatanPenduduk,
            'tanggal_awal' => $validated['tanggal_awal'],
            'tanggal_akhir' => $validated['tanggal_akhir'],
            'kegiatan' => $validated['kegiatan'],
        ]);

        return redirect()->route('karyawan.kegiatan.index')
            ->with('success', 'Data kegiatan Karyawan berhasil ditambahkan.');
    }

    /**
     * Update an existing Kegiatan for the logged-in Karyawan (Update).
     */
    public function kegiatanUpdate(Request $request, int $idSettingsKegiatan): RedirectResponse
    {
        $karyawan = $this->getCurrentKaryawan($request);
        $kegiatan = SettingsKegiatan::findOrFail($idSettingsKegiatan);

        if (! $request->filled('kegiatan') && $request->filled('deskripsi_kegiatan')) {
            $request->merge(['kegiatan' => $request->input('deskripsi_kegiatan')]);
        }

        $validated = $request->validate([
            'id_jabatan' => ['nullable', 'integer', 'exists:jabatan,id_jabatan'],
            'id_jabatan_penduduk' => ['nullable', 'integer', 'exists:jabatan_penduduk,id_jabatan_penduduk'],
            'tanggal_awal' => ['required', 'date'],
            'tanggal_akhir' => ['required', 'date', 'after_or_equal:tanggal_awal'],
            'kegiatan' => ['required', 'string', 'max:255'],
        ]);

        $idJabatanPenduduk = $this->resolveKaryawanJabatanPendudukId($karyawan, $validated);

        $kegiatan->update([
            'id_jabatan_penduduk' => $idJabatanPenduduk,
            'tanggal_awal' => $validated['tanggal_awal'],
            'tanggal_akhir' => $validated['tanggal_akhir'],
            'kegiatan' => $validated['kegiatan'],
        ]);

        return redirect()->route('karyawan.kegiatan.index')
            ->with('success', 'Data kegiatan Karyawan berhasil diperbarui.');
    }

    /**
     * Delete a Kegiatan for the logged-in Karyawan (Delete).
     */
    public function kegiatanDestroy(int $idSettingsKegiatan): RedirectResponse
    {
        $kegiatan = SettingsKegiatan::findOrFail($idSettingsKegiatan);
        $kegiatan->delete();

        return redirect()->route('karyawan.kegiatan.index')
            ->with('success', 'Data kegiatan Karyawan berhasil dihapus.');
    }

    /**
     * Retrieve the currently authenticated Karyawan (Penduduk).
     */
    private function getCurrentKaryawan(Request $request): Penduduk
    {
        /** @var Penduduk|null $karyawan */
        $karyawan = $request->attributes->get('currentKaryawan');

        if ($karyawan !== null) {
            return $karyawan;
        }

        return Penduduk::with('jabatanPenduduk.jabatan')
            ->findOrFail((int) $request->session()->get('penduduk_id'));
    }

    /**
     * Resolve or create the id_jabatan_penduduk for the given Karyawan.
     *
     * @param  array<string, mixed>  $validated
     */
    private function resolveKaryawanJabatanPendudukId(Penduduk $karyawan, array $validated): int
    {
        if (! empty($validated['id_jabatan_penduduk'])) {
            return (int) $validated['id_jabatan_penduduk'];
        }

        if (! empty($validated['id_jabatan'])) {
            $assignment = JabatanPenduduk::resolvePair($karyawan->id_penduduk, (int) $validated['id_jabatan']);

            return $assignment->id_jabatan_penduduk;
        }

        $firstAssignment = $karyawan->jabatanPenduduk()->first();
        if ($firstAssignment !== null) {
            return $firstAssignment->id_jabatan_penduduk;
        }

        $defaultJabatan = Jabatan::first() ?? Jabatan::create(['nama_jabatan' => 'Staff Karyawan']);

        $assignment = JabatanPenduduk::resolvePair($karyawan->id_penduduk, $defaultJabatan->id_jabatan);

        return $assignment->id_jabatan_penduduk;
    }
}
