<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\JabatanPenduduk;
use App\Models\Kunjungan;
use App\Models\Pemohon;
use App\Models\Penduduk;
use App\Models\SettingsKegiatan;
use App\Models\SettingsKunjungan;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display Pengajuan Kunjungan list for Admin (Read).
     */
    public function kunjunganIndex(Request $request): View
    {
        $query = Kunjungan::with([
            'pemohon',
            'settingsKunjungan.jabatanPenduduk.penduduk',
            'settingsKunjungan.jabatanPenduduk.jabatan',
        ]);

        if ($request->filled('status')) {
            $status = strtoupper((string) $request->query('status'));
            if (in_array($status, ['Y', 'T'], true)) {
                $query->where('konfrimasi_admin', $status);
            } elseif ($status === 'PENDING') {
                $query->whereNull('konfrimasi_admin');
            }
        }

        $kunjunganList = $query->orderBy('id_kunjungan', 'desc')->get();

        $stats = [
            'total_kunjungan' => Kunjungan::count(),
            'menunggu_admin' => Kunjungan::whereNull('konfrimasi_admin')->count(),
            'disetujui_admin' => Kunjungan::where('konfrimasi_admin', 'Y')->count(),
            'ditolak_admin' => Kunjungan::where('konfrimasi_admin', 'T')->count(),
            'total_pemohon' => Pemohon::count(),
        ];

        return view('admin.kunjungan', compact('kunjunganList', 'stats'));
    }

    /**
     * Update Pengajuan Kunjungan confirmation by Admin (Update: status Y/T & catatan ditolak).
     */
    public function kunjunganUpdate(Request $request, int $idKunjungan): RedirectResponse
    {
        $kunjungan = Kunjungan::findOrFail($idKunjungan);

        $statusInput = strtoupper((string) ($request->input('status') ?? $request->input('konfrimasi_admin')));
        $request->merge(['status_normalized' => $statusInput]);

        $validated = $request->validate([
            'status_normalized' => ['required', 'in:Y,T'],
            'catatan_admin' => [$statusInput === 'T' ? 'required' : 'nullable', 'string', 'max:255'],
        ], [
            'catatan_admin.required' => 'Catatan ditolak wajib diisi apabila status pengajuan ditolak (T).',
        ]);

        $kunjungan->update([
            'konfrimasi_admin' => $validated['status_normalized'],
            'tanggal_waktu_admin' => Carbon::now()->format('Y-m-d H:i:s'),
            'catatan_admin' => $validated['status_normalized'] === 'T'
                ? $validated['catatan_admin']
                : ($validated['catatan_admin'] ?? null),
        ]);

        return redirect()->route('admin.kunjungan.index')
            ->with('success', 'Status pengajuan kunjungan oleh Admin berhasil diperbarui ('.$validated['status_normalized'].').');
    }

    /**
     * Delete a Kunjungan record (Delete).
     */
    public function kunjunganDestroy(int $idKunjungan): RedirectResponse
    {
        $kunjungan = Kunjungan::findOrFail($idKunjungan);
        $kunjungan->delete();

        return redirect()->route('admin.kunjungan.index')
            ->with('success', 'Data pengajuan kunjungan berhasil dihapus.');
    }

    /**
     * Display Setting Kunjungan management page for Admin (Read).
     */
    public function settingsKunjunganIndex(): View
    {
        $settingsList = SettingsKunjungan::with([
            'jabatanPenduduk.penduduk',
            'jabatanPenduduk.jabatan',
        ])->orderBy('tanggal', 'desc')
            ->orderBy('jam_aktif', 'asc')
            ->get();

        $pendudukList = Penduduk::with('jabatanPenduduk.jabatan')
            ->orderBy('nama_penduduk', 'asc')
            ->get();

        $jabatanList = Jabatan::orderBy('nama_jabatan', 'asc')->get();

        return view('admin.settings_kunjungan', compact('settingsList', 'pendudukList', 'jabatanList'));
    }

    /**
     * Store a new Setting Kunjungan by Admin (Create: id_penduduk, jabatan, tanggal, jam_aktif, jam_non_aktif, status Y/T).
     */
    public function settingsKunjunganStore(Request $request): RedirectResponse
    {
        $statusNormalized = strtoupper((string) $request->input('status', 'Y'));
        $request->merge(['status' => $statusNormalized]);

        $validated = $request->validate([
            'id_penduduk' => ['required_without:id_jabatan_penduduk', 'nullable', 'integer', 'exists:penduduk,id_penduduk'],
            'id_jabatan' => ['required_without:id_jabatan_penduduk', 'nullable', 'integer', 'exists:jabatan,id_jabatan'],
            'id_jabatan_penduduk' => ['nullable', 'integer', 'exists:jabatan_penduduk,id_jabatan_penduduk'],
            'tanggal' => ['required', 'date'],
            'jam_aktif' => ['required'],
            'jam_non_aktif' => ['required'],
            'status' => ['required', 'in:Y,T'],
        ]);

        $idJabatanPenduduk = $this->resolveJabatanPendudukId($validated);

        SettingsKunjungan::create([
            'id_jabatan_penduduk' => $idJabatanPenduduk,
            'tanggal' => $validated['tanggal'],
            'jam_aktif' => $validated['jam_aktif'],
            'jam_non_aktif' => $validated['jam_non_aktif'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.settings-kunjungan.index')
            ->with('success', 'Setting jadwal kunjungan berhasil ditambahkan.');
    }

    /**
     * Update an existing Setting Kunjungan by Admin (Update).
     */
    public function settingsKunjunganUpdate(Request $request, int $idSettingsKunjungan): RedirectResponse
    {
        $setting = SettingsKunjungan::findOrFail($idSettingsKunjungan);

        $statusNormalized = strtoupper((string) $request->input('status', 'Y'));
        $request->merge(['status' => $statusNormalized]);

        $validated = $request->validate([
            'id_penduduk' => ['required_without:id_jabatan_penduduk', 'nullable', 'integer', 'exists:penduduk,id_penduduk'],
            'id_jabatan' => ['required_without:id_jabatan_penduduk', 'nullable', 'integer', 'exists:jabatan,id_jabatan'],
            'id_jabatan_penduduk' => ['nullable', 'integer', 'exists:jabatan_penduduk,id_jabatan_penduduk'],
            'tanggal' => ['required', 'date'],
            'jam_aktif' => ['required'],
            'jam_non_aktif' => ['required'],
            'status' => ['required', 'in:Y,T'],
        ]);

        $idJabatanPenduduk = $this->resolveJabatanPendudukId($validated);

        $setting->update([
            'id_jabatan_penduduk' => $idJabatanPenduduk,
            'tanggal' => $validated['tanggal'],
            'jam_aktif' => $validated['jam_aktif'],
            'jam_non_aktif' => $validated['jam_non_aktif'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.settings-kunjungan.index')
            ->with('success', 'Setting jadwal kunjungan berhasil diperbarui.');
    }

    /**
     * Delete a Setting Kunjungan by Admin (Delete).
     */
    public function settingsKunjunganDestroy(int $idSettingsKunjungan): RedirectResponse
    {
        $setting = SettingsKunjungan::findOrFail($idSettingsKunjungan);
        $setting->delete();

        return redirect()->route('admin.settings-kunjungan.index')
            ->with('success', 'Setting jadwal kunjungan berhasil dihapus.');
    }

    /**
     * Display Kegiatan (settings_kegiatan) management page for Admin (Read).
     */
    public function kegiatanIndex(): View
    {
        $kegiatanList = SettingsKegiatan::with([
            'jabatanPenduduk.penduduk',
            'jabatanPenduduk.jabatan',
        ])->orderBy('tanggal_awal', 'desc')->get();

        $pendudukList = Penduduk::with('jabatanPenduduk.jabatan')
            ->orderBy('nama_penduduk', 'asc')
            ->get();

        $jabatanList = Jabatan::orderBy('nama_jabatan', 'asc')->get();

        return view('admin.kegiatan', compact('kegiatanList', 'pendudukList', 'jabatanList'));
    }

    /**
     * Store a new Kegiatan by Admin (Create: id_penduduk, jabatan, tanggal_awal, tanggal_akhir, deskripsi kegiatan).
     */
    public function kegiatanStore(Request $request): RedirectResponse
    {
        if (! $request->filled('kegiatan') && $request->filled('deskripsi_kegiatan')) {
            $request->merge(['kegiatan' => $request->input('deskripsi_kegiatan')]);
        }

        $validated = $request->validate([
            'id_penduduk' => ['required_without:id_jabatan_penduduk', 'nullable', 'integer', 'exists:penduduk,id_penduduk'],
            'id_jabatan' => ['required_without:id_jabatan_penduduk', 'nullable', 'integer', 'exists:jabatan,id_jabatan'],
            'id_jabatan_penduduk' => ['nullable', 'integer', 'exists:jabatan_penduduk,id_jabatan_penduduk'],
            'tanggal_awal' => ['required', 'date'],
            'tanggal_akhir' => ['required', 'date', 'after_or_equal:tanggal_awal'],
            'kegiatan' => ['required', 'string', 'max:255'],
        ]);

        $idJabatanPenduduk = $this->resolveJabatanPendudukId($validated);

        SettingsKegiatan::create([
            'id_jabatan_penduduk' => $idJabatanPenduduk,
            'tanggal_awal' => $validated['tanggal_awal'],
            'tanggal_akhir' => $validated['tanggal_akhir'],
            'kegiatan' => $validated['kegiatan'],
        ]);

        return redirect()->route('admin.kegiatan.index')
            ->with('success', 'Data kegiatan berhasil ditambahkan.');
    }

    /**
     * Update an existing Kegiatan by Admin (Update).
     */
    public function kegiatanUpdate(Request $request, int $idSettingsKegiatan): RedirectResponse
    {
        $kegiatan = SettingsKegiatan::findOrFail($idSettingsKegiatan);

        if (! $request->filled('kegiatan') && $request->filled('deskripsi_kegiatan')) {
            $request->merge(['kegiatan' => $request->input('deskripsi_kegiatan')]);
        }

        $validated = $request->validate([
            'id_penduduk' => ['required_without:id_jabatan_penduduk', 'nullable', 'integer', 'exists:penduduk,id_penduduk'],
            'id_jabatan' => ['required_without:id_jabatan_penduduk', 'nullable', 'integer', 'exists:jabatan,id_jabatan'],
            'id_jabatan_penduduk' => ['nullable', 'integer', 'exists:jabatan_penduduk,id_jabatan_penduduk'],
            'tanggal_awal' => ['required', 'date'],
            'tanggal_akhir' => ['required', 'date', 'after_or_equal:tanggal_awal'],
            'kegiatan' => ['required', 'string', 'max:255'],
        ]);

        $idJabatanPenduduk = $this->resolveJabatanPendudukId($validated);

        $kegiatan->update([
            'id_jabatan_penduduk' => $idJabatanPenduduk,
            'tanggal_awal' => $validated['tanggal_awal'],
            'tanggal_akhir' => $validated['tanggal_akhir'],
            'kegiatan' => $validated['kegiatan'],
        ]);

        return redirect()->route('admin.kegiatan.index')
            ->with('success', 'Data kegiatan berhasil diperbarui.');
    }

    /**
     * Delete a Kegiatan by Admin (Delete).
     */
    public function kegiatanDestroy(int $idSettingsKegiatan): RedirectResponse
    {
        $kegiatan = SettingsKegiatan::findOrFail($idSettingsKegiatan);
        $kegiatan->delete();

        return redirect()->route('admin.kegiatan.index')
            ->with('success', 'Data kegiatan berhasil dihapus.');
    }

    /**
     * Display Struktur Karyawan / Penduduk & Jabatan management page for Admin.
     */
    public function karyawanIndex(): View
    {
        $pendudukList = Penduduk::with('jabatanPenduduk.jabatan')
            ->orderBy('id_penduduk', 'asc')
            ->get();

        $jabatanList = Jabatan::orderBy('id_jabatan', 'asc')->get();

        $jabatanPendudukList = JabatanPenduduk::with(['penduduk', 'jabatan'])
            ->orderBy('id_jabatan_penduduk', 'asc')
            ->get();

        return view('admin.karyawan', compact('pendudukList', 'jabatanList', 'jabatanPendudukList'));
    }

    /**
     * Store a new Karyawan / Penduduk and assign their Jabatan.
     */
    public function karyawanStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_penduduk' => ['required', 'string', 'max:100'],
            'nik' => ['required', 'string', 'size:16'],
            'kk' => ['required', 'string', 'size:16'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'alamat' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:4'],
            'status' => ['required', 'in:Admin,Penduduk'],
            'id_jabatan' => ['nullable', 'integer', 'exists:jabatan,id_jabatan'],
            'status_jabatan' => ['nullable', 'in:Y,T'],
        ]);

        $penduduk = Penduduk::create([
            'nama_penduduk' => $validated['nama_penduduk'],
            'nik' => $validated['nik'],
            'kk' => $validated['kk'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'alamat' => $validated['alamat'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'status' => $validated['status'],
        ]);

        if (! empty($validated['id_jabatan'])) {
            JabatanPenduduk::create([
                'id_penduduk' => $penduduk->id_penduduk,
                'id_jabatan' => (int) $validated['id_jabatan'],
                'status' => $validated['status_jabatan'] ?? 'Y',
            ]);
        }

        return redirect()->route('admin.karyawan.index')
            ->with('success', 'Data Karyawan / Penduduk berhasil ditambahkan.');
    }

    /**
     * Update an existing Karyawan / Penduduk and their Jabatan assignment.
     */
    public function karyawanUpdate(Request $request, int $idPenduduk): RedirectResponse
    {
        $penduduk = Penduduk::findOrFail($idPenduduk);

        $validated = $request->validate([
            'nama_penduduk' => ['required', 'string', 'max:100'],
            'nik' => ['required', 'string', 'size:16'],
            'kk' => ['required', 'string', 'size:16'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'alamat' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:4'],
            'status' => ['required', 'in:Admin,Penduduk'],
            'id_jabatan' => ['nullable', 'integer', 'exists:jabatan,id_jabatan'],
            'status_jabatan' => ['nullable', 'in:Y,T'],
        ]);

        $updateData = [
            'nama_penduduk' => $validated['nama_penduduk'],
            'nik' => $validated['nik'],
            'kk' => $validated['kk'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'alamat' => $validated['alamat'],
            'username' => $validated['username'],
            'status' => $validated['status'],
        ];

        if (! empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $penduduk->update($updateData);

        if (! empty($validated['id_jabatan'])) {
            $assignment = JabatanPenduduk::where('id_penduduk', $penduduk->id_penduduk)->first();
            if ($assignment !== null) {
                $assignment->update([
                    'id_jabatan' => (int) $validated['id_jabatan'],
                    'status' => $validated['status_jabatan'] ?? 'Y',
                ]);
            } else {
                JabatanPenduduk::create([
                    'id_penduduk' => $penduduk->id_penduduk,
                    'id_jabatan' => (int) $validated['id_jabatan'],
                    'status' => $validated['status_jabatan'] ?? 'Y',
                ]);
            }
        }

        return redirect()->route('admin.karyawan.index')
            ->with('success', 'Data Karyawan / Penduduk berhasil diperbarui.');
    }

    /**
     * Delete a Karyawan / Penduduk record.
     */
    public function karyawanDestroy(int $idPenduduk): RedirectResponse
    {
        $penduduk = Penduduk::findOrFail($idPenduduk);
        JabatanPenduduk::where('id_penduduk', $idPenduduk)->delete();
        $penduduk->delete();

        return redirect()->route('admin.karyawan.index')
            ->with('success', 'Data Karyawan / Penduduk berhasil dihapus.');
    }

    /**
     * Store a new Jabatan.
     */
    public function jabatanStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_jabatan' => ['required', 'string', 'max:100'],
        ]);

        Jabatan::create($validated);

        return redirect()->route('admin.karyawan.index')
            ->with('success', 'Data Jabatan berhasil ditambahkan.');
    }

    /**
     * Update an existing Jabatan.
     */
    public function jabatanUpdate(Request $request, int $idJabatan): RedirectResponse
    {
        $jabatan = Jabatan::findOrFail($idJabatan);

        $validated = $request->validate([
            'nama_jabatan' => ['required', 'string', 'max:100'],
        ]);

        $jabatan->update($validated);

        return redirect()->route('admin.karyawan.index')
            ->with('success', 'Data Jabatan berhasil diperbarui.');
    }

    /**
     * Delete a Jabatan.
     */
    public function jabatanDestroy(int $idJabatan): RedirectResponse
    {
        $jabatan = Jabatan::findOrFail($idJabatan);
        $jabatan->delete();

        return redirect()->route('admin.karyawan.index')
            ->with('success', 'Data Jabatan berhasil dihapus.');
    }

    /**
     * Resolve or create the id_jabatan_penduduk from request input.
     *
     * @param  array<string, mixed>  $validated
     */
    private function resolveJabatanPendudukId(array $validated): int
    {
        if (! empty($validated['id_jabatan_penduduk'])) {
            return (int) $validated['id_jabatan_penduduk'];
        }

        $jabatanPenduduk = JabatanPenduduk::resolvePair(
            (int) $validated['id_penduduk'],
            (int) $validated['id_jabatan']
        );

        return $jabatanPenduduk->id_jabatan_penduduk;
    }
}
