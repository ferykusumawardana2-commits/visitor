<?php

namespace Tests\Feature;

use App\Models\Jabatan;
use App\Models\JabatanPenduduk;
use App\Models\Kunjungan;
use App\Models\Pemohon;
use App\Models\Penduduk;
use App\Models\SettingsKegiatan;
use App\Models\SettingsKunjungan;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisitorPtAbcdTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_landing_pages_render_successfully(): void
    {
        $this->get(route('landing.home'))
            ->assertStatus(200)
            ->assertSee('Si Kunjung Pemda');

        $this->get(route('landing.jadwal'))
            ->assertStatus(200)
            ->assertSee('Jadwal Kunjungan');

        $this->get(route('landing.struktur'))
            ->assertStatus(200)
            ->assertSee('Struktur Karyawan / Penduduk Si Kunjung Pemda');
    }

    public function test_pemohon_can_register_login_and_perform_kunjungan_crud_and_view_histori(): void
    {
        // 1. Daftar (Create)
        $registerResponse = $this->post(route('pemohon.register.store'), [
            'nama' => 'Andika Pemohon',
            'telepon' => '081122334455',
            'email' => 'andika@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $registerResponse->assertRedirect(route('pemohon.kunjungan.index'));
        $this->assertDatabaseHas('pemohon', ['email' => 'andika@example.com']);

        // 2. Logout & Login (Read)
        $this->post(route('pemohon.logout'));
        $loginResponse = $this->post(route('pemohon.login.authenticate'), [
            'email' => 'andika@example.com',
            'password' => 'secret123',
        ]);
        $loginResponse->assertRedirect(route('pemohon.kunjungan.index'));

        $jadwal = SettingsKunjungan::firstOrFail();

        // 3. Create Kunjungan
        $this->post(route('pemohon.kunjungan.store'), [
            'id_settings_kunjungan' => $jadwal->id_settings_kunjungan,
            'tujuannya' => 'Diskusi Proyek Konstruksi Si Kunjung Pemda',
            'tanggal_waktu' => '2026-11-15 10:00:00',
        ])->assertRedirect(route('pemohon.kunjungan.index'));

        $kunjungan = Kunjungan::where('tujuannya', 'Diskusi Proyek Konstruksi Si Kunjung Pemda')->firstOrFail();

        // 4. Read Kunjungan & Histori
        $this->get(route('pemohon.kunjungan.index'))
            ->assertStatus(200)
            ->assertSee('Diskusi Proyek Konstruksi Si Kunjung Pemda');

        $this->get(route('pemohon.histori'))
            ->assertStatus(200)
            ->assertSee('Diskusi Proyek Konstruksi Si Kunjung Pemda');

        // 5. Update Kunjungan
        $this->put(route('pemohon.kunjungan.update', $kunjungan->id_kunjungan), [
            'id_settings_kunjungan' => $jadwal->id_settings_kunjungan,
            'tujuannya' => 'Diskusi Proyek Konstruksi Si Kunjung Pemda (Revisi)',
            'tanggal_waktu' => '2026-11-16 11:00:00',
        ])->assertRedirect(route('pemohon.kunjungan.index'));

        $this->assertDatabaseHas('kunjungan', [
            'id_kunjungan' => $kunjungan->id_kunjungan,
            'tujuannya' => 'Diskusi Proyek Konstruksi Si Kunjung Pemda (Revisi)',
        ]);

        // 6. Delete Kunjungan
        $this->delete(route('pemohon.kunjungan.destroy', $kunjungan->id_kunjungan))
            ->assertRedirect(route('pemohon.kunjungan.index'));

        $this->assertDatabaseMissing('kunjungan', [
            'id_kunjungan' => $kunjungan->id_kunjungan,
        ]);
    }

    public function test_admin_can_login_and_manage_pengajuan_kunjungan_settings_and_kegiatan(): void
    {
        // Admin Login
        $this->post(route('admin.login.authenticate'), [
            'username' => 'admin',
            'password' => 'admin123',
        ])->assertRedirect(route('admin.kunjungan.index'));

        $kunjungan = Kunjungan::firstOrFail();

        // Admin Rejects Kunjungan (status T requires catatan_admin)
        $this->put(route('admin.kunjungan.update', $kunjungan->id_kunjungan), [
            'status' => 'T',
            'catatan_admin' => 'Mohon maaf dokumen belum lengkap.',
        ])->assertRedirect(route('admin.kunjungan.index'));

        $this->assertDatabaseHas('kunjungan', [
            'id_kunjungan' => $kunjungan->id_kunjungan,
            'konfrimasi_admin' => 'T',
            'catatan_admin' => 'Mohon maaf dokumen belum lengkap.',
        ]);

        // Admin Setting Kunjungan CRUD
        $penduduk = Penduduk::where('status', 'Penduduk')->firstOrFail();
        $jabatan = Jabatan::firstOrFail();

        $this->post(route('admin.settings-kunjungan.store'), [
            'id_penduduk' => $penduduk->id_penduduk,
            'id_jabatan' => $jabatan->id_jabatan,
            'tanggal' => '2026-12-01',
            'jam_aktif' => '08:00:00',
            'jam_non_aktif' => '15:00:00',
            'status' => 'y',
        ])->assertRedirect(route('admin.settings-kunjungan.index'));

        $setting = SettingsKunjungan::where('tanggal', '2026-12-01')->firstOrFail();
        $this->assertSame('Y', $setting->status);

        $this->put(route('admin.settings-kunjungan.update', $setting->id_settings_kunjungan), [
            'id_penduduk' => $penduduk->id_penduduk,
            'id_jabatan' => $jabatan->id_jabatan,
            'tanggal' => '2026-12-02',
            'jam_aktif' => '09:00:00',
            'jam_non_aktif' => '16:00:00',
            'status' => 't',
        ])->assertRedirect(route('admin.settings-kunjungan.index'));

        $this->assertDatabaseHas('settings_kunjungan', [
            'id_settings_kunjungan' => $setting->id_settings_kunjungan,
            'tanggal' => '2026-12-02',
            'status' => 'T',
        ]);

        // Admin Kegiatan CRUD
        $this->post(route('admin.kegiatan.store'), [
            'id_penduduk' => $penduduk->id_penduduk,
            'id_jabatan' => $jabatan->id_jabatan,
            'tanggal_awal' => '2026-12-05',
            'tanggal_akhir' => '2026-12-07',
            'kegiatan' => 'Kunjungan Kerja Cabang Surabaya',
        ])->assertRedirect(route('admin.kegiatan.index'));

        $kegiatan = SettingsKegiatan::where('kegiatan', 'Kunjungan Kerja Cabang Surabaya')->firstOrFail();

        $this->put(route('admin.kegiatan.update', $kegiatan->id_settings_kegiatan), [
            'id_penduduk' => $penduduk->id_penduduk,
            'id_jabatan' => $jabatan->id_jabatan,
            'tanggal_awal' => '2026-12-06',
            'tanggal_akhir' => '2026-12-08',
            'kegiatan' => 'Kunjungan Kerja Cabang Surabaya & Malang',
        ])->assertRedirect(route('admin.kegiatan.index'));

        $this->assertDatabaseHas('settings_kegiatan', [
            'id_settings_kegiatan' => $kegiatan->id_settings_kegiatan,
            'kegiatan' => 'Kunjungan Kerja Cabang Surabaya & Malang',
        ]);

        $this->delete(route('admin.kegiatan.destroy', $kegiatan->id_settings_kegiatan))
            ->assertRedirect(route('admin.kegiatan.index'));
    }

    public function test_karyawan_can_login_and_manage_kunjungan_settings_and_kegiatan(): void
    {
        // Karyawan Login
        $this->post(route('karyawan.login.authenticate'), [
            'username' => 'budi',
            'password' => 'karyawan123',
        ])->assertRedirect(route('karyawan.kunjungan.index'));

        $kunjungan = Kunjungan::firstOrFail();

        // Karyawan Rejects Kunjungan (status T + catatan ditolak)
        $this->put(route('karyawan.kunjungan.update', $kunjungan->id_kunjungan), [
            'status' => 'T',
            'catatan_penduduk' => 'Sedang dinas luar kota pada jam tersebut.',
        ])->assertRedirect(route('karyawan.kunjungan.index'));

        $this->assertDatabaseHas('kunjungan', [
            'id_kunjungan' => $kunjungan->id_kunjungan,
            'konfrimasi_penduduk' => 'T',
            'catatan_penduduk' => 'Sedang dinas luar kota pada jam tersebut.',
        ]);

        // Karyawan Setting Kunjungan CRUD
        $jabatan = Jabatan::firstOrFail();
        $this->post(route('karyawan.settings-kunjungan.store'), [
            'id_jabatan' => $jabatan->id_jabatan,
            'tanggal' => '2026-12-10',
            'jam_aktif' => '10:00:00',
            'jam_non_aktif' => '14:00:00',
            'status' => 'Y',
        ])->assertRedirect(route('karyawan.settings-kunjungan.index'));

        $this->assertDatabaseHas('settings_kunjungan', [
            'tanggal' => '2026-12-10',
            'status' => 'Y',
        ]);

        // Karyawan Kegiatan CRUD
        $this->post(route('karyawan.kegiatan.store'), [
            'id_jabatan' => $jabatan->id_jabatan,
            'tanggal_awal' => '2026-12-12',
            'tanggal_akhir' => '2026-12-14',
            'kegiatan' => 'Workshop Kepemimpinan Divisi Si Kunjung Pemda',
        ])->assertRedirect(route('karyawan.kegiatan.index'));

        $this->assertDatabaseHas('settings_kegiatan', [
            'kegiatan' => 'Workshop Kepemimpinan Divisi Si Kunjung Pemda',
        ]);
    }
}
